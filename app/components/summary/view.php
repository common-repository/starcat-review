<?php

namespace StarcatReview\App\Components\Summary;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Summary\View')) {
    class View
    {
        public function __construct()
        {}

        public function get(array $props)
        {
            // error_log('[$props] : ' . print_r($props, true));
            $author_summary = $this->get_author_summary($props);
            $users_summary = $this->get_users_summary($props);

            $author_prosandcons_summary = $this->get_author_prosandcons_summary($props);
            $users_attachments = $this->get_users_attachments($props);

            $html = '';
            $html .= $props['collection']['reviews_title'];
            $html .= '<div class="scr-summary">';
            $html .= '<div class="ui stackable ' . $props['collection']['no_of_column'] . ' column grid">';
            $html .= '<div class="row scr-row">';
            $html .= $author_summary;
            $html .= $users_summary;
            $html .= '</div>';
            $html .= $author_prosandcons_summary;
            $html .= $users_attachments;
            $html .= '</div></div>';
            return $html;

        }

        public function get_column($args, $stat_args)
        {
            $html = '';
            $is_stat_set = isset($stat_args['items']) && !empty($stat_args['items']) ? true : false;

            if (!$is_stat_set) {
                return $html;
            }
            $title = isset($args['title']) ? $args['title'] : '';
            $class = isset($args['class']) ? $args['class'] : '';

            $stats = new \StarcatReview\App\Components\Stats\Controller($stat_args);
            $stats_view = $stats->get_view();

            $html .= '<div class="column ' . $class . '">';
            $html .= '<h4 class="ui header">' . $title . ' </h4>';
            $html .= $stats_view;
            $html .= '</div>';

            return $html;
        }

        public function get_all_attachments($props)
        {
            $html = '';
            $attachments = (isset($props['items']['attachments']) && !empty($props['items']['attachments'])) ? $props['items']['attachments'] : [];

            if (isset($attachments) && !empty($attachments)) {
                $all_photos = apply_filters('scr_photo_reviews/get_all_photos', $attachments);
                $html .= is_string($all_photos) ? $all_photos : '';
            }

            return $html;
        }

        public function get_author_summary(array $props)
        {
            $html = '';
            if ($props['collection']['is_enable_author']) {
                $args = array(
                    'title' => $props['collection']['author_title'],
                    'class' => 'scr-author_review',
                );
                $html = $this->get_column($args, $props['items']['author_stat']);
            }
            return $html;
        }

        public function get_users_summary(array $props)
        {
            $html = '';
            $show_user_review = (isset($props['collection']['is_enable_user_review']) && $props['collection']['is_enable_user_review'] == true) ? true : false;
            if ($show_user_review == true) {
                $args = array(
                    'title' => $props['collection']['users_title'],
                    'class' => 'scr-user_review',
                );
                $html = $this->get_column($args, $props['items']['comment_stat']);
            }
            return $html;
        }

        public function get_author_prosandcons_summary(array $props)
        {
            $html = '';
            $enable_pros_and_cons = (isset($props['collection']['is_enable_prosandcons']) && $props['collection']['is_enable_prosandcons'] == true) ? true : false;
            if ($enable_pros_and_cons == true) {
                $prosandcons = new \StarcatReview\App\Components\ProsAndCons\Controller();
                $html = $prosandcons->get_view($props);
            }
            return $html;
        }

        public function get_users_attachments(array $props)
        {
            $html = '';
            $show_user_review = (isset($props['collection']['is_enable_user_review']) && $props['collection']['is_enable_user_review'] == true) ? true : false;
            $show_attachments = (isset($props['collection']['is_enable_attachments']) && $props['collection']['is_enable_attachments'] == true) ? true : false;

            if ($show_user_review == true && $show_attachments == true) {
                $html = $this->get_all_attachments($props);
            }
            return $html;
        }
    }
}