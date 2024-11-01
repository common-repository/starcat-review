<?php

namespace StarcatReview\Includes;

use StarcatReview\Includes\Settings\SCR_Getter;
use \StarcatReview\Services\Recaptcha as Recaptcha;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Ajax_Handler')) {
    class Ajax_Handler
    {
        public function __construct()
        {
        }

        public function register_ajax_actions()
        {
            // add 'ajax' action when not logged in
            add_action('wp_ajax_nopriv_scr_listing_action', array($this, 'scr_listing_action'));
            add_action('wp_ajax_scr_listing_action', array($this, 'scr_listing_action'));

            // add 'ajax' action when not logged in
            add_action('wp_ajax_nopriv_scr_user_review_submission', [$this, 'user_review_submission']);
            add_action('wp_ajax_scr_user_review_submission', [$this, 'user_review_submission']);

            // add 'ajax' action when not logged in
            add_action('wp_ajax_nopriv_scr_search_posts', [$this, 'search_posts']);
            add_action('wp_ajax_scr_search_posts', [$this, 'search_posts']);

            // Ajax Hooks In compare table
            add_action('wp_ajax_nopriv_get_scr_results', [$this, 'get_scr_results']);
            add_action('wp_ajax_get_scr_results', [$this, 'get_scr_results']);

            // Vote Submission ajax for User Review
            add_action('wp_ajax_nopriv_scr_user_review_vote', [$this, 'vote_handler']);
            add_action('wp_ajax_scr_user_review_vote', [$this, 'vote_handler']);

            // Delete a single attachment from a photos review
            add_action('wp_ajax_nopriv_scr_pr_delete_attachment', [$this, 'delete_review_attachment']);
            add_action('wp_ajax_scr_pr_delete_attachment', [$this, 'delete_review_attachment']);

            // Aajax for Photos Review
            add_action('wp_ajax_nopriv_scr_photo_reviews', [$this, 'photo_reviews']);
            add_action('wp_ajax_scr_photo_reviews', [$this, 'photo_reviews']);
        }

        public function photo_reviews()
        {
            /**
             * Escape, sanitization and validation done for below $_POST global in its own add_filter callback's
             */
            $response = apply_filters('scr_photo_reviews/ajax', $_POST);
            echo json_encode($response);
            wp_die();
        }

        public function scr_listing_action()
        {

            if (isset($_GET['search'])) {
                $search_query = sanitize_text_field(wp_unslash($_GET['search']));

                // Check the query variable is available
                // If not, global it so it can be read from
                if (!$wp_query) {
                    global $wp_query;
                }

                $query = array(
                    'the_title' => $search_query,
                    // 'the_content' => $search_query
                );

                $search_results = new WP_Query($query);

                // error_log("Results : " .print_r($search_results, true));
                echo json_encode($search_results);
            }
            return 1;
        }

        public function user_review_submission()
        {
            $post_id = isset($_POST['post_id']) && !empty($_POST['post_id']) ? $_POST['post_id'] : 0;
            $post = get_post($post_id);
            $is_enabled_captcha = SCR_Getter::get('ur_show_captcha');
            if (isset($post) && $post->post_type == 'product') {
                $is_enabled_captcha = SCR_Getter::get('woo_show_captcha');
            }

            $is_reply_form = (isset($_POST['form_action_type']) && $_POST['form_action_type'] == 'reply') ? true : false;
            if ($is_enabled_captcha && !$is_reply_form) {
                $captcha_success = Recaptcha::verify();
                error_log('captcha_success : ' . $captcha_success);
                if ($captcha_success == false) {
                    error_log('*** captcha Failed ***');
                    echo json_encode("BOT!");
                    wp_die();
                }
                // error_log('captcha_success : ' . $captcha_success);
            }

            $user_review_repo = new \StarcatReview\App\Repositories\User_Reviews_Repo();
            $props = $user_review_repo->get_processed_data();

            $comment_id = (isset($props['methodType']) && $props['methodType'] === 'PUT') ? $user_review_repo->update($props) : $user_review_repo->insert($props);
            $review = $user_review_repo->get($comment_id, $props['parent']);

            if ($props['parent'] !== 0 && !isset($props['methodType'])) { // review_reply
                $review_controller = new \StarcatReview\App\Components\User_Reviews\Controller();
                $review = $review_controller->get_comment_view($comment_id, 'reply');
            }

            echo json_encode($review);
            wp_die();
        }

        public function vote_handler()
        {
            $user_review_repo = new \StarcatReview\App\Repositories\User_Reviews_Repo();
            $props = $user_review_repo->get_processed_voting_data();
            // error_log('props : ' . print_r($props, true));
            $user_review_repo->store_vote($props);
            $props = $user_review_repo->get($props['comment_id']);

            echo json_encode($props);

            wp_die();
        }

        public function delete_review_attachment()
        {
            $pr_repo = new \StarcatReviewPhotoReviews\Includes\Repository();
            $props = $pr_repo->get_processing_attachment_data();
            $pr_repo->delete_attachment($props);

            wp_send_json($props);
        }

        public function search_posts()
        {
            /** TODO: since - v0.7.6 - use 'user_review_enabled_post_types' instead of "review_enable_post-types" */
            //$scr_enabled_post_types = SCR_Getter::get('review_enable_post-types');
            $scr_enabled_post_types = SCR_Getter::get_review_enabled_post_types();
            $args = array(
                'post_type' => $scr_enabled_post_types,
                'post_status' => array('publish'),
                'nopaging' => true,
                'order' => 'ASC',
                'orderby' => 'menu_order',
            );
            $get_global_stats = SCR_Getter::get_global_stats();
            $global_stats = array();
            if (count($get_global_stats) > 0) {
                foreach ($get_global_stats as $stat) {
                    $global_stats[] = strtoupper($stat['stat_name']);
                }
            }

            $results = new \WP_Query($args);
            $posts = array();
            if ($results->have_posts()) {

                foreach ($results->posts as $post) {

                    if (has_post_thumbnail($post->ID)) {
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));
                    }

                    $get_scr_user_reviews = scr_get_user_reviews($post->ID);
                    $process_src_user_reviews = $this->process_user_stat_reviews($get_scr_user_reviews, $global_stats);

                    // error_log("post" . print_r($post, true));
                    $post_tilte = wp_strip_all_tags($post->post_title);
                    if (strlen($post_tilte) > 60) {
                        $post_tilte = substr($post->post_title, 0, 60) . '...';
                    }
                    $get_scr_overall_rating = scr_get_overall_rating($post->ID);
                    $posts[] = array(
                        'id' => $post->ID,
                        'title' => $post_tilte,
                        'description' => substr(wp_strip_all_tags($post->post_content), 0, 46) . '...',
                        // 'url' => $post->guid,
                        'image_url' => isset($image) ? $image[0] : "",
                        'user_stats' => $process_src_user_reviews,
                        'get_overall_stat' => $get_scr_overall_rating,
                    );
                }
            }

            echo json_encode($posts);
            wp_die();
        }

        public function process_user_stat_reviews($args, $global_stats)
        {
            $stats = array();
            error_log('ajax args : ' . print_r($args, true));
            // 1. $args equals or less than 0, finally return $args
            if (count($args) == 0 && count($global_stats) > 0) {

                $get_not_found_user_reviews = $this->not_found_user_reviews($global_stats);
                $stats['rating'] = 0;
                $stats['review_stats'] = $get_not_found_user_reviews;

            } else {
                // 2. foreach for the $args greater than zero condition
                foreach ($args as $post_user_reviews) {
                    $reviews = isset($post_user_reviews->reviews) ? $post_user_reviews->reviews : [];

                    // 2.1 If $reviews not set, the continue
                    if (count($reviews) == 0) {
                        continue;
                    } else {
                        $stats['ratings'] = $reviews['rating'];
                        $user_stats = $reviews['stats'];

                        $active_user_review_stats = array();
                        $active_user_review_stats['scr_ct_ratings'] = array('stat_name' => 'scr_rating', 'value' => 0);

                        if (count($user_stats) > 0) {
                            /***
                             * get user stats it only found in global stats, else then cant get that user stat
                             * */
                            foreach ($user_stats as $stat_index => $single_stat) {
                                $stat_index = strtoupper($stat_index);
                                if (in_array($stat_index, $global_stats)) {
                                    $active_user_review_stats[$stat_index] = $single_stat;
                                }
                            }
                        }

                        if (count($active_user_review_stats) > 0) {
                            /**
                             *  global stats founds some new stats, but not found in user post reviews.
                             *  because user's not rate those stat's , that stat's are get and assign the rate as 0
                             */
                            foreach ($global_stats as $global_stat) {
                                $stat_name = strtoupper($global_stat);

                                if (array_key_exists($stat_name, $active_user_review_stats)) {
                                    // is found key
                                } else {
                                    $active_user_review_stats[$stat_name] = array(
                                        'stat_name' => $stat_name,
                                        'rating' => 0,
                                    );
                                }
                            }
                        }

                        $stats['review_stats'] = $active_user_review_stats;
                    }
                }
            }

            return $stats;
        }

        public function not_found_user_reviews($global_stats)
        {
            $stat_args = array();

            $stat_args['scr_ct_ratings'] = array('stat_name' => 'scr_rating', 'value' => 0);
            foreach ($global_stats as $stat_name) {
                $stat_name = strtoupper($stat_name);
                $stat_args[$stat_name] = array(
                    'stat_name' => strtoupper($stat_name),
                    'rating' => 0,
                );
            }
            return $stat_args;
        }

        public function get_scr_results()
        {
            //get scr resultSets
            //echo "get scr resultSets";
            $search_key = sanitize_text_field(wp_unslash($_REQUEST['search_key']));
            $comparison_controller = new \StarcatReview\App\Components\Comparison\Controller();
            $scr_search_result_sets = $comparison_controller->get_scr_details($search_key);
            wp_die();
        }
    }
}