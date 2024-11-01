<?php

namespace StarcatReview\App\Builders;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Builders\Review_Builder')) {
    class Review_Builder
    {
        public function get_reviews()
        {
            $html = '';

            $user_review_handler = new \StarcatReview\App\Widget_Makers\User_Review\Handler();
            $user_review_lists = new \StarcatReview\App\Widget_Makers\User_Review\Lists();
            $user_review_form = new \StarcatReview\App\Widget_Makers\User_Review\Form();

            $args = $user_review_handler->get_default_args();

            /** show (or) don't-show the user review form & list */
            $can_show_the_user_review_form_and_list = $this->can_show_the_user_review_by_post_args($args);

            if ($can_show_the_user_review_form_and_list) {
                $html .= $user_review_form->get_form($args);
                $html .= $user_review_lists->get_lists_view($args);
            }
            return $html;
        }

        public function get_summary_content(array $post_reviews_caps_args = array())
        {
            $user_review_summary = new \StarcatReview\App\Widget_Makers\User_Review\Summary();
            $settings_args = $user_review_summary->get_settings_args();
            $settings_args = array_merge($settings_args, $post_reviews_caps_args);
            $html = $user_review_summary->get_summary_view($settings_args);
            return $html;
        }

        public function can_show_the_user_review_by_post_args($args)
        {
            // get post_user_review_settings args from post meta
            $post_user_review_settings = isset($args['post_user_review_settings']) && !empty($args['post_user_review_settings']) ? $args['post_user_review_settings'] : [];

            $can_show_the_user_review = false;
            // return false, if empty the user review args
            if (empty($post_user_review_settings)) {
                return $can_show_the_user_review;
            }

            // post level checking for show/don't show the user review form and list
            $post_level_settings = new \StarcatReview\App\Post_Settings\Post_Level_Settings();
            $can_show_the_user_review = $post_level_settings->can_show_the_review($post_user_review_settings, 'can_show_user_review');
            return $can_show_the_user_review;
        }

    } // END CLASS

}