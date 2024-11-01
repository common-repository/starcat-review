<?php

namespace StarcatReview\App\Services;

use \StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/*
 *  Use Cases for listing, post_overall, summary_author and summary_users
 */
if (!class_exists('\StarcatReview\App\Services\Comments_Factory')) {
    class Comments_Factory
    {

        public function get_comments_args($use_cases = ['stats'], $query_args)
        {
            $identical = 'stats';
            $comments = [];
            $comment_ids = $this->get_comments_ids($query_args);
            
            if ($this->is_set($comment_ids)) {
                foreach ($comment_ids as $comment_id) {

                    $review = get_comment_meta($comment_id, SCR_COMMENT_META, true);

                    foreach ($use_cases as $case) {
                        $item = [];
                        $identical = $case;

                        if ($case == 'stats') {
                            $item = $this->get_stat($query_args, $comment_id, $review);
                        }

                        if ($case == 'prosandcons' && SCR_Getter::is_enabled_pros_cons()) {
                            $item = $this->get_proandcon($review);
                        }

                        if ($case == 'votes') {
                            $item = $this->get_vote($review);
                        }

                        if ($case == 'attachments' && SCR_Getter::get('pr_enable')) {
                            $item = $this->get_attachment($comment_id, $review);
                        }

                        if ($case == 'comments') {
                            $item = scr_get_comment($comment_id, $review);
                        }

                        // Non_empty item only added to comments array
                        if ($this->is_set($item)) {
                            $comments[$case][$comment_id] = $item;
                        }
                    }
                }

            }

            // Returns asked single component. Defualt component is 'stats'
            if (count($use_cases) == 1 && array_key_exists($identical, $comments)) {
                return $comments[$identical];
            }

            return $comments;

        }

        public function get_comment($comment_id, $review = [])
        {
            $comment = [];
            $comment_obj = get_comment($comment_id);
            $author = isset($comment_obj->comment_author) && !empty($comment_obj->comment_author) ? ucfirst($comment_obj->comment_author) : __('Anonymous', SCR_DOMAIN);

            $comment = [
                'ID' => $comment_obj->comment_ID,
                'post_ID' => $comment_obj->comment_post_ID,
                'author' => $author,
                'author_IP' => $comment_obj->comment_author_IP,
                'email' => $comment_obj->comment_author_email,
                'website' => $comment_obj->comment_author_url,
                'user_id' => $comment_obj->user_id,
                'avatar' => get_avatar($comment_obj->user_id),
                'content' => $comment_obj->comment_content,
                'parent' => $comment_obj->comment_parent,
                'approved' => $comment_obj->comment_approved,
                'date' => get_comment_date('', $comment_obj->comment_ID),
                'time' => $this->get_comment_time($comment_obj->comment_date),
                'time_stamp' => get_comment_date('U', $comment_obj->comment_ID),
            ];

            // error_log('comment : ' . print_r($comment_obj, true));
            if ($this->is_key_exist('title', $review)) {
                $comment['title'] = $review['title'];
            }

            return $comment;
        }

        protected function get_comments_ids($query_args)
        {
            $comment_ids = [];
            $query = [
                'post_id' => isset($query_args['post_id']) ? $query_args['post_id'] : get_the_ID(),
                'type' => isset($query_args['type']) ? $query_args['type'] : SCR_COMMENT_TYPE,
                'parent' => isset($query_args['parent']) ? $query_args['parent'] : 0,
                'status' => isset($query_args['status']) ? $query_args['status'] : '',
                'user_id' => isset($query_args['user_id']) ? $query_args['user_id'] : '',
            ];

            $comments = get_comments($query);

            $comment_ids = wp_list_pluck($comments, 'comment_ID');

            return $comment_ids;
        }

        protected function get_stat($args, $comment_id, $review)
        {
            $stat_item = [];

            if ($this->is_key_exist('stats', $review)) {
                $stat_item = apply_filters('scr_stat', $review['stats']);
            }

            $post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
            // WooCommerce product post_type Only
            if (get_post_type($post_id) == 'product' && !$this->is_set($stat_item) && SCR_Getter::get('enable_reviews_on_woocommerce')) {
                
                $rating = apply_filters('scr_woocommerce_integration/convert_product_rating_to_stat', $comment_id);

                if ($this->is_set($rating) && is_array($rating)) {
                    $stat_item = apply_filters('scr_stat', $rating);
                }
            }

            return $stat_item;

        }

        protected function get_vote($review)
        {
            $summary = [];

            if ($this->is_key_exist('votes', $review)) {
                $summary = [
                    'likes' => 0,
                    'dislikes' => 0,
                    'active' => 0,
                    'people' => 0,
                ];

                foreach ($review['votes'] as $vote) {

                    // Is active Like or DisLike or Not
                    if (get_current_user_id() == $vote['user_id']) {
                        if (($vote['vote'] == 1)) {
                            $summary['active'] = 'like';
                        } elseif (($vote['vote'] == -1)) {
                            $summary['active'] = 'dislike';
                        } else {
                            $summary['active'] = 0;
                        }
                    }

                    // Likes
                    if ($vote['vote'] == 1) {
                        $summary['likes']++;
                    }

                    // Dislikes
                    if ($vote['vote'] == -1) {
                        $summary['dislikes']++;
                    }
                    // poeple
                    $summary['people']++;
                }
            }

            return $summary;
        }

        protected function get_proandcon($review)
        {
            $proandcon = [];

            if ($this->is_key_exist('pros', $review)) {
                $proandcon['items']['pros-list'] = $review['pros'];
            }
            if ($this->is_key_exist('cons', $review)) {
                $proandcon['items']['cons-list'] = $review['cons'];
            }

            return $proandcon;

        }

        /*
        TODO: have list of sizes like medium, large, small, full
         */

        protected function get_attachment($comment_id, $review)
        {
            $attachment = [];
            if ($this->is_key_exist('attachments', $review)) {
                foreach ($review['attachments'] as $attachment_id) {
                    $attachment[] = [
                        'id' => $attachment_id,
                        'review_id' => $comment_id,
                        'url' => wp_get_attachment_image_src($attachment_id, 'medium')[0],
                    ];
                }
            }

            return $attachment;
        }

        private function is_key_exist($key, $array)
        {
            $is_key_exist = true;
            if (((is_array($array)) && (!array_key_exists($key, $array))) || (!is_array($array))) {
                $is_key_exist = false;
            }
            return $is_key_exist;
        }

        private function is_set($given)
        {
            $is_set = false;
            if (isset($given) && !empty($given)) {
                $is_set = true;
            }
            return $is_set;
        }

        private function get_comment_time($date)
        {
            $date = mysql2date(get_option('time_format'), $date, true);

            return apply_filters('get_comment_time', $date);
        }
    }
}
