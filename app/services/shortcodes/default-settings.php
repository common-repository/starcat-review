<?php

namespace StarcatReview\App\Services\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Services\Shortcodes\Default_Settings')) {
    class Default_Settings
    {
        public function get_user_review_args()
        {
            $args = [
                'post_id' => 0,
                'show_stats' => 1,
                'show_form' => 1,
                'show_lists' => 1,
                'show_summary' => 1,
            ];

            $args = array_merge($args, $this->get_user_review_list_args());
            $args = array_merge($args, $this->get_review_summary_args());

            return $args;
        }

        public function get_user_review_list_args()
        {
            $args = [
                'post_id' => 0,
                'show_review_form' => 1,
                'show_review_search' => 1,
                'show_review_title' => 1,
                'show_review_sort' => 1,
                'use_of_form' => 'edit_review',
            ];
            return $args;
        }

        public function get_review_summary_args()
        {
            $args = [
                'post_id' => 0,
                'show_author_reviews_summary' => 1,
                'show_user_reviews_summary' => 1,
                'show_pros_and_cons_summary' => 1,
            ];
            return $args;
        }
    }
}