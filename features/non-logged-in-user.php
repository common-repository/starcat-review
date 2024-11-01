<?php

namespace StarcatReview\Features;

use \StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Features\Non_Logged_In_User')) {
    class Non_Logged_In_User
    {
        public function __construct()
        {
            // error_log('\StarcatReview\Features\Non_Logged_In_User');
            add_action('init', array($this, 'init_hook'));
        }

        public function get_settings()
        {
            $settings = [
                'who_can_review' => SCR_Getter::get('ur_who_can_review'),
            ];
            return $settings;
        }

        public function init_hook()
        {

            $current_user = new \StarcatReview\App\Services\User();

            if ($current_user->is_loggedin()) {
                return;
            }

            add_filter('scr_user_form_start', [$this, 'form_modification'], 10, 2);
            add_filter('scr_user_form_end', [$this, 'form_comment_consent_modification'], 10, 2);

            add_filter('scr_form_process_data', [$this, 'process_form']);
            add_filter('scr_comment', [$this, 'get_can_edit_comment_capabilities']);
            add_filter('scr_can_view_comment', [$this, 'can_view_comment'], 10, 2);
            add_filter('scr_has_current_user_already_reviewed', [$this, 'has_current_user_already_reviewed'], 10, 2);
        }

        public function get_can_edit_comment_capabilities($comment_item)
        {
            // return if current_user is not a non-logged-in-user
            if (isset($comment_item['user_id']) && $comment_item['user_id'] != 0) {
                return $comment_item;
            }

            $current_user = new \StarcatReview\App\Services\User();
            $current_user_IP = $current_user->get_user_IP();

            $comment_item['can_edit'] = false;

            // Non-Logged-in-users
            if ($comment_item['author_IP'] == $current_user_IP) {
                $comment_item['can_edit'] = true;
            }

            return $comment_item;
        }

        public function can_view_comment($can_view, $comment)
        {
            // error_log('$comment_info : ' . print_r($comment_info, true));
            if (!is_array($comment)) {
                error_log('CHECK DATA TYPE OF : ' . print_r($comment, true));
            }

            // Exit Rule 1 for this hook
            if ($comment['approved'] == 1) {
                return $can_view;
            }

            // Exit Rule 2 for this hook
            if (isset($comment['user_id']) && $comment['user_id'] != 0) {
                return $can_view;
            }

            // If user is logged_in, this method should not be called at all.
            $Current_User = new \StarcatReview\App\Services\User();
            $current_user_IP = $Current_User->get_user_IP();
            $settings = $this->get_settings();

            if ($settings['who_can_review'] == 'everyone' && $comment['author_IP'] == $current_user_IP) {
                $can_view = true;
            } else {
                $can_view = false;
            }

            return $can_view;
        }

        public function process_form($props = [])
        {

            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $props['name'] = sanitize_text_field($_POST['name']);
            }

            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $props['email'] = sanitize_email($_POST['email']);
            }

            if (isset($_POST['website']) && !empty($_POST['website'])) {
                $props['website'] = esc_url_raw($_POST['website']);
            }

            if (isset($_POST['wp-comment-cookies-consent']) && !empty($_POST['wp-comment-cookies-consent'])) {
                $props['wp-comment-cookies-consent'] = intval($_POST['wp-comment-cookies-consent']);
            }

            return $props;
        }

        public function has_current_user_already_reviewed($has_reviewed, $comment)
        {
            // error_log('$has_current_user_already_reviewed COMMENT : ' . print_r($comment, true));
            // Exit Rule 1 for this hook
            if ((isset($comment['user_id']) && $comment['user_id'] != 0)) {
                return $has_reviewed;
            }

            $Current_User = new \StarcatReview\App\Services\User();
            $current_user_IP = $Current_User->get_user_IP();

            if ($comment['author_IP'] == $current_user_IP && $comment['parent'] == 0) {
                $has_reviewed = true;
            } else {
                $has_reviewed = false;
            }

            return $has_reviewed;
        }

        public function form_modification($html = '', $review = [])
        {
            $commenter = wp_get_current_commenter();

            $name = (isset($commenter['comment_author'])) ? $commenter['comment_author'] : '';
            $email = (isset($commenter['comment_author_email'])) ? $commenter['comment_author_email'] : '';
            $website = (isset($commenter['comment_author_url'])) ? $commenter['comment_author_url'] : '';

            $html .= '<div class="inline field">';
            // $html .= '<label>Review Title</label>';
            $html .= '<input type="text" name="name" placeholder="' . __('Name', SCR_DOMAIN) . '*" value="' . $name . '"/>';
            $html .= '</div>';

            $html .= '<div class="inline field">';
            // $html .= '<label>Review Title</label>';
            $html .= '<input type="text" name="email" placeholder="' . __('Email', SCR_DOMAIN) . '*" value="' . $email . '"/>';
            $html .= '</div>';

            $html .= '<div class="inline field">';
            // $html .= '<label>Review Title</label>';
            $html .= '<input type="text" name="website" placeholder="' . __('Website', SCR_DOMAIN) . '" value="' . $website . '"/>';
            $html .= '</div>';

            return $html;
        }

        public function form_comment_consent_modification($html = '', $review)
        {
            $commenter = wp_get_current_commenter();

            $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
            $html .= '<div class="field">';
            $html .= '<div class="ui checkbox wp-scr-comment-cookies-consent">';
            $html .= '<input id="wp-scr-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />';
            $html .= '<label for="wp-scr-comment-cookies-consent">' . __('Save my name, email, and website in this browser for the next time I comment.', SCR_DOMAIN) . '</label>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }
    } // END CLASS
}
