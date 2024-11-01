<?php

namespace StarcatReview\App\Widget_Makers\User_Review;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Widget_Makers\User_Review\Lists')) {
    class Lists
    {
        public function get_lists_view(array $args, array $user_args = array())
        {
            $user_review_controller = new \StarcatReview\App\Components\User_Reviews\Controller();
            $reviews_list_view = $user_review_controller->get_view($args, $user_args);

            $wrapper_start_html = '<div id="scr-controlled-list" class="scr-user-controlled-list" data-collectionprops="{<pagination<:true,<page<:9,<type<:2}">';
            $controls_builder = new \StarcatReview\App\Builders\Controls_Builder('user_review');

            $args = $this->get_lists_controls_args($user_args);
            $controls_view = $controls_builder->get_controls($args);
            $view = $wrapper_start_html . $controls_view . $reviews_list_view . '</div>';

            return $view;
        }

        public function get_lists_controls_args(array $user_args = array())
        {
            $args = [
                'search' => 1,
                'sort' => 1,
            ];

            if (empty($user_args)) {
                return $args;
            }
            if (isset($user_args['show_review_search'])) {
                $args['search'] = $user_args['show_review_search'];
            }

            if (isset($user_args['show_review_sort'])) {
                $args['sort'] = $user_args['show_review_sort'];
            }
            return $args;
        }

    }
}
