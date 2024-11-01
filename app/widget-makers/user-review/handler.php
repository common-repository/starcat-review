<?php

namespace StarcatReview\App\Widget_Makers\User_Review;

use StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Widget_Makers\User_Review\Handler')) {
    class Handler
    {

        public function get_default_args(array $user_args = array())
        {
            $args = $this->get_settings_args($user_args);
            $args = $this->get_interpreted_args($args, $user_args);
            return $args;
        }

        public function get_settings_args(array $user_args = array())
        {
            $stat_args = ['stats_args' => SCR_Getter::get_stat_default_args()];
            $args = $this->get_general_settings_args($user_args);
            $args = $this->get_woocommerce_settings_args($args, $user_args);
            $args = array_merge($stat_args, $args);
            return $args;
        }

        public function get_interpreted_args(array $args, array $user_args = array())
        {
            $post_id = $this->get_the_post_id_from_user_args($user_args);
            $post_meta = get_post_meta($post_id, SCR_POST_META, true);
            $args['pros-list'] = [];
            $args['cons-list'] = [];
            $args['post_author_review_settings'] = [];
            $args['post_user_review_settings'] = [];

            if (isset($post_meta['pros-list']) && !empty($post_meta['pros-list'])) {
                $args['pros-list'] = $post_meta['pros-list'];
            }
            if (isset($post_meta['cons-list']) && !empty($post_meta['cons-list'])) {
                $args['cons-list'] = $post_meta['cons-list'];
            }

            if (isset($post_meta['post_author_review_settings']) && !empty($post_meta['post_author_review_settings'])) {
                $args['post_author_review_settings'] = $post_meta['post_author_review_settings'];
            }

            if (isset($post_meta['post_user_review_settings']) && !empty($post_meta['post_user_review_settings'])) {
                $args['post_user_review_settings'] = $post_meta['post_user_review_settings'];
            }

            $components = ['comments', 'stats', 'prosandcons', 'votes', 'attachments'];
            $args['items'] = scr_get_comments_args($components, $user_args);
            $commentsItems = (isset($args['items']['comments']) && !empty($args['items']['comments'])) ? $args['items']['comments'] : [];
            $args['capability'] = apply_filters('scr_capabilities_args', $commentsItems);

            return $args;
        }

        public function get_general_settings_args(array $params)
        {

            $args = [
                'post_id' => $this->get_the_post_id_from_user_args($params),
                // 'enable_user_reviews' => SCR_Getter::get('enable_user_reviews'),
                'enable_user_reviews' => true,
                'enable_pros_cons' => SCR_Getter::get('enable-pros-cons'),
                'enable_photo_reviews' => SCR_Getter::get('pr_enable'),
                'show_list_title' => SCR_Getter::get('ur_show_list_title'),
                'list_title' => SCR_Getter::get('ur_list_title'),
                'enable_voting' => SCR_Getter::get('ur_enable_voting'),
                'show_form_title' => SCR_Getter::get('ur_show_form_title'),
                'form_title' => SCR_Getter::get('ur_form_title'),
                'show_title' => SCR_Getter::get('ur_show_title'),
                'show_stats' => SCR_Getter::get('ur_show_stats'),
                'show_description' => SCR_Getter::get('ur_show_description'),
                'show_captcha' => SCR_Getter::get('ur_show_captcha'),
                'current_user_id' => get_current_user_id(),
                'author_review_enabled_post_types' => SCR_Getter::get('author_review_enabled_post_types'),
            ];
            return $args;
        }

        public function get_woocommerce_settings_args(array $args, array $user_args = array())
        {
            global $post;
            if (!is_singular('product') && isset($post) && $post->post_type != 'product') {
                return $args;
            }
            $args['enable_pros_cons'] = SCR_Getter::get('woo_enable_pros_cons');
            $args['enable_voting'] = SCR_Getter::get('woo_enable_voting');
            $args['show_form_title'] = SCR_Getter::get('woo_show_form_title');
            $args['show_captcha'] = SCR_Getter::get('woo_show_captcha');
            $args['enable_user_reviews'] = true;
            return $args;
        }

        public function get_the_post_id_from_user_args(array $user_args = array())
        {
            $post_id = isset($user_args['post_id']) && !empty($user_args['post_id']) ? $user_args['post_id'] : get_the_ID();
            return $post_id;
        }

        public function get_component(string $component_type, array $args)
        {
            //1. return, if the component is empty
            if (empty($component_type)) {
                return;
            }

        }
    }
}