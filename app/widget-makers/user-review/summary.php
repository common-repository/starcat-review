<?php

namespace StarcatReview\App\Widget_Makers\User_Review;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
use StarcatReview\Includes\Settings\SCR_Getter;

if (!class_exists('\StarcatReview\App\Widget_Makers\User_Review\Summary')) {
    class Summary
    {
        public function get_summary_view(array $args, array $user_args = array())
        {
            $summary = new \StarcatReview\App\Components\Summary\Controller();
            return $summary->get_view($args, $user_args);
        }

        public function get_settings_args(array $user_args = array())
        {
            $post_id = isset($user_args['post_id']) ? $user_args['post_id'] : get_the_ID();
            $args = [
                'enable-author-review' => SCR_Getter::get('enable-author-review'),
                'enable_pros_cons' => SCR_Getter::is_enabled_pros_cons(),
                'review_count' => scr_get_user_reviews_count($post_id),
            ];
            $args = array_merge($args, $this->get_default_args($user_args));
            return $args;
        }

        public function get_default_args(array $user_args = array())
        {
            $post_id = isset($user_args['post_id']) ? $user_args['post_id'] : get_the_ID();
            $args = SCR_Getter::get_stat_default_args();
            $args['post_id'] = $post_id;
            $args['items'] = $this->get_items($user_args);
            return $args;
        }

        protected function get_items(array $user_args = array())
        {
            $post_id = isset($user_args['post_id']) ? $user_args['post_id'] : get_the_ID();

            $post_meta = get_post_meta($post_id, SCR_POST_META, true);

            $items = [];

            $items['summary_author'] = scr_get_stat_args($post_id, 'author_stat');
            $items['summary_users'] = scr_get_stat_args($post_id, 'comment_stat');

            if (isset($post_meta['pros-list']) && !empty($post_meta['pros-list'])) {
                $items['pros-list'] = $post_meta['pros-list'];
            }
            if (isset($post_meta['cons-list']) && !empty($post_meta['cons-list'])) {
                $items['cons-list'] = $post_meta['cons-list'];
            }

            $items['attachments'] = scr_get_comments_args(['attachments'], $user_args);

            return $items;
        }
    }
}