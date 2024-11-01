<?php

namespace StarcatReview\App\Widget_Makers\Review_Listing;

// use \StarcatReview\App\Abstracts\Widget_Model_Interface as Widget_Model_Interface;
// use \StarcatReview\App\Abstracts\Widget_Model as Widget_Model;
use \StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Widget_Makers\Review_Listing\Controller')) {
    class Controller
    {
        public function __construct()
        {
            $this->fields_model = new \StarcatReview\App\Widget_Makers\Review_Listing\Fields_Model();
            $this->style_config = new \StarcatReview\App\Widget_Makers\Review_Listing\Style_Config_Model();
            // parent::__construct($this->fields_model);
        }

        public function load()
        {}

        public function get_view($args)
        {
            $default_args = $this->get_default_args();
            $component_args = array_merge($default_args, $args);
            $component_args = $this->get_interpreted_args($component_args);
            $component_args = $this->boolean_conversion($component_args);

            $listing_controller = new \StarcatReview\App\Components\Listing\Controller();
            return $listing_controller->get_view($component_args);
        }

        protected function get_interpreted_args($component_args)
        {
            $posts = !isset($component_args['posts']) ? $this->get_default_posts() : $component_args['posts'];

            /* Adding Stat HTML to $post objects */
            $posts = $this->get_combine_rating($posts);
            // error_log('posts : ' . print_r($posts, true));

            $component_args = $this->get_post_listing_args($component_args, $posts);

            return $component_args;
        }

        protected function get_post_listing_args($component_args, $posts)
        {
            $items = [];
            foreach ($posts as $key => $post) {
                $review_count = scr_get_user_reviews_count($post->ID);
                $items[] = [
                    'title' => $post->post_title,
                    'featured_image' => SCR_URL . 'includes/assets/img/dummy-review.jpg',
                    'content' => substr(wp_strip_all_tags($post->post_content), 0, 100) . '...',
                    // 'content' => get_the_excerpt($post->ID),
                    'pre_content_html' => $post->stat_html,
                    'url' => get_post_permalink($post->ID),
                    'columns' => $component_args['num_of_cols'],
                    'items_display' => $component_args['items_display'] ? $component_args['items_display'] : ['title', 'content', 'link'],
                    'meta_data' => [
                        'review_count' => $review_count,
                        'date' => get_post_time('U', 'false', $post->ID),
                        'modified_date' => get_post_modified_time('U', 'false', $post->ID),
                        'trendScore' => scr_get_trend_score($post->ID),
                        'positiveScore' => scr_get_overall_rating($post->ID)['overall']['rating'],
                    ],
                ];
            }

            $component_args['items'] = $items;

            return $component_args;
        }

        public function get_fields()
        {
            return $this->fields_model->get_fields();
        }

        public function get_default_args()
        {
            $args = array();

            // Get Default Values from GET - FIELDS
            $fields = $this->get_fields();
            foreach ($fields as $key => $field) {
                $args[$key] = $field['default'];
            }

            return $args;
        }

        /* For Elementor Widget */
        public function get_style_config()
        {
            return $this->style_config->get_config();
        }

        /* PROTECTED METHODS */
        protected function boolean_conversion($args)
        {
            foreach ($args as $key => $arg) {

                if ($arg == 'on') {
                    $args[$key] = true;
                } else if ($arg == 'off') {
                    $args[$key] = false;
                }
            }

            return $args;
        }

        protected function get_default_posts()
        {
            $posts = [];

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'posts_per_page' => -1,
                'post_type' => SCR_POST_TYPE,
                'paged' => $paged,
            );

            $query = new \WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    // Optionally, pick parts of the post and create a custom collection.
                    $query->the_post();
                    $posts[] = get_post();
                }
                wp_reset_postdata();
            }

            return $posts;
        }

        protected function get_combine_rating($posts)
        {
            foreach ($posts as $key => $post) {
                $args = SCR_Getter::get_stat_default_args();
                $args['post_id'] = $post->ID;

                $args['combine_type'] = 'overall';
                $author_review = get_post_meta($post->ID, SCR_POST_META, true);
                $args['items'] = isset($author_review) && !empty($author_review) ? $author_review : [];
                $comments = $this->get_comments_list($post->ID);
                if (isset($comments) && !empty($comments)) {
                    $args['items']['comments-list'] = $comments;
                }
                if (!empty($args['items'])) {
                    $stats_controller = new \StarcatReview\App\Components\Stats\Controller($args);
                    $post->stat_html = $stats_controller->get_view();
                }
            }

            return $posts;
        }

        protected function get_comments_list($post_id)
        {
            $args = [
                'post_id' => $post_id,
                'type' => SCR_COMMENT_TYPE,
            ];

            $comments = get_comments($args);

            foreach ($comments as $comment) {
                $comment->reviews = get_comment_meta($comment->comment_ID, SCR_COMMENT_META, true);
            }

            return $comments;
        }
    } // END CLASS
}
