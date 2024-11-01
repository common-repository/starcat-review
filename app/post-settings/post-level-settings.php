<?php

namespace StarcatReview\App\Post_Settings;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
use StarcatReview\Includes\Settings\SCR_Getter;

if (!class_exists('\StarcatReview\App\Post_Settings\Post_Level_Settings')) {
    class Post_Level_Settings
    {
        public function get_author_and_user_reviews_settings(array $params = array())
        {
            $post_id = get_the_ID();
            $post_meta = get_post_meta($post_id, SCR_POST_META, true);
            $post_settings_args = array(
                'post_author_review_settings' => array(),
                'post_user_review_settings' => array(),
            );

            foreach ($post_settings_args as $key => $value) {
                if (isset($post_meta[$key]) && !empty($post_meta[$key])) {
                    $post_settings_args[$key] = $post_meta[$key];
                }
            }
            // error_log('[$post_settings_args] : ' . print_r($post_settings_args, true));
            return $post_settings_args;
        }

        public function get_summary_args_by_post_settings(array $post_settings_args)
        {
            $args = array(
                'before' => array(),
                'after' => array(),
            );

            // get single post user and author settings
            $author_reviews_settings_args = $post_settings_args['post_author_review_settings'];
            $user_reviews_settings_args = $post_settings_args['post_user_review_settings'];

            $can_show_author_review = isset($author_reviews_settings_args['can_show_author_review']) ? $author_reviews_settings_args['can_show_author_review'] : 'apply_global_settings';

            /** enable/disable the pros and cons summary.  */
            $enable_pros_cons = $this->check_enabled_pros_and_cons($can_show_author_review);

            $allowed_locations = ['after', 'before'];
            /** get author and user reviews custom locations from post settings  */
            $author_review_custom_location = isset($author_reviews_settings_args['custom_location']) && $author_reviews_settings_args['custom_location'] == 1 ? true : false;
            $user_review_custom_location = isset($user_reviews_settings_args['custom_location']) && $user_reviews_settings_args['custom_location'] == 1 ? true : false;

            /** get author and user reviews locations from post settings  */
            $author_review_location = isset($author_reviews_settings_args['location']) ? $author_reviews_settings_args['location'] : 'after';
            $user_review_location = isset($user_reviews_settings_args['location']) ? $user_reviews_settings_args['location'] : 'after';

            $author_review_location = ($author_review_custom_location == 1 && in_array($author_review_location, $allowed_locations)) ? $author_reviews_settings_args['location'] : 'after';
            $user_review_location = ($user_review_custom_location == 1 && in_array($user_review_location, $allowed_locations)) ? $user_reviews_settings_args['location'] : 'after';

            $can_show_author_reviews = $this->can_show_the_review($author_reviews_settings_args, 'can_show_author_review');
            $can_show_users_reviews = $this->can_show_the_review($user_reviews_settings_args, 'can_show_user_review');

            /** show both reviews */
            $can_show_the_review = ($can_show_author_reviews && $can_show_users_reviews) ? 'both' : 'none';
            if ($can_show_the_review == 'none') {
                /** show author reviews only */
                $can_show_the_review = (!$can_show_users_reviews && $can_show_author_reviews) ? 'auth_reviews' : 'none';
                /** show users reviews only, else show the author review (or) none of both*/
                $can_show_the_review = ($can_show_users_reviews && !$can_show_author_reviews) ? 'user_reviews' : $can_show_the_review;
            }

            $enable_author_review = ($can_show_the_review == 'both' || $can_show_the_review == 'auth_reviews') ? true : false;
            $enable_user_review = ($can_show_the_review == 'both' || $can_show_the_review == 'user_reviews') ? true : false;

            foreach ($args as $key => $value) {

                /** Get the default summary args */
                $summary_args = $this->get_default_summary_args($key);

                $author_review = ($enable_author_review && $author_review_location == $key) ? true : false;
                $user_review = ($enable_user_review && $user_review_location == $key) ? true : false;
                $show_pros_and_cons = ($enable_pros_cons && $author_review) ? true : false;

                $summary_args['enable-author-review'] = $author_review;
                $summary_args['enable_pros_cons'] = $show_pros_and_cons;
                $summary_args['enable_user_reviews'] = $user_review;

                $args[$key] = $summary_args;
            }

            return $args;
        }

        public function can_show_the_review(array $review_settings, string $review_type)
        {
            // 1. Show / Don't Show Local
            $display_the_review = isset($review_settings[$review_type]) ? $review_settings[$review_type] : 'apply_global_settings';

            // 1.1 Don't Show
            if ($display_the_review == 'dont_show') {
                return false;
            }

            // 1.2 Show + Shortcode
            // check the user (or) author review as custom location or not.
            $custom_location = (isset($review_settings['custom_location']) && $review_settings['custom_location'] == 1) ? true : false;

            // don't show the user (or) author reviews if users choose the "location" option value as shortcode.
            $use_shortcode = ($custom_location && $review_settings['location'] == 'shortcode') ? true : false;

            if ($use_shortcode) {
                return false;
            }

            // 1.3 Show + No Shortcode
            if ($display_the_review == 'show') {
                return true;
            }

            // 2. Applying Global Settings
            $post_type = get_post_type();

            /** should check "enable_reviews_on_woocommerce" option is enabled/disabled if current post_type is product  */
            $reviews_enabled_in_product = ($post_type == 'product' && SCR_Getter::get('enable_reviews_on_woocommerce') == true) ? true : false;

            $can_show_the_review = false;
            if ($review_type == 'can_show_author_review') {

                if ($reviews_enabled_in_product) {
                    return true;
                }

                // show the author reviews or not by global settings
                $author_review_enabled_post_types = SCR_Getter::get('author_review_enabled_post_types');

                if (empty($author_review_enabled_post_types)) {
                    return false;
                }

                $can_show_the_review = (in_array($post_type, $author_review_enabled_post_types)) ? true : false;

            }

            if ($review_type == 'can_show_user_review') {

                if ($reviews_enabled_in_product) {
                    return true;
                }

                // show the user reviews or not by global settings
                $user_review_enabled_post_types = SCR_Getter::get('user_review_enabled_post_types');

                if (empty($user_review_enabled_post_types)) {
                    return false;
                }

                $can_show_the_review = (in_array($post_type, $user_review_enabled_post_types)) ? true : false;
            }

            return $can_show_the_review;
        }

        public function get_default_summary_args(string $type_of_location = 'after')
        {
            $args = array(
                'enable-author-review' => 0,
                'enable_pros_cons' => 0,
                'enable_user_reviews' => 0,
                'enable_atthachments' => ($type_of_location == 'after') ? 1 : 0,
            );

            return $args;
        }

        private function check_enabled_pros_and_cons($can_show_author_review)
        {
            if ($can_show_author_review == 'dont_show') {
                return false;
            }

            if ($can_show_author_review == 'show') {
                return true;
            }

            $post_type = get_post_type();
            $enable_pros_cons = SCR_Getter::get('enable-pros-cons');

            if ($post_type != 'product') {
                $author_review_enabled_post_types = SCR_Getter::get('author_review_enabled_post_types');
                if (empty($author_review_enabled_post_types)) {
                    return false;
                }
                return (in_array($post_type, $author_review_enabled_post_types)) && $enable_pros_cons ? true : false;
            }

            if ($post_type == 'product') {
                $enable_pros_cons = SCR_Getter::get('woo_enable_pros_cons');
                return (SCR_Getter::get('enable_reviews_on_woocommerce') == true && $enable_pros_cons) ? true : false;
            }
            return false;
        }
    }
}