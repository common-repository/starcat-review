<?php

namespace StarcatReview\App\Repositories;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Repositories\User_Reviews_Repo')) {
    class User_Reviews_Repo
    {
        public function __construct()
        {
            $this->current_user = new \StarcatReview\App\Services\User();
        }
        public function get($comment_id, $parent = 0)
        {
            if ($parent != 0) {
                return get_comment($comment_id);
            }

            return get_comment_meta($comment_id, SCR_COMMENT_META);
        }

        public function insert($props)
        {
            $user_can_review = $this->current_user->can_review();
            $can_approve = $this->current_user->can_user_directly_publish_reviews();

            // 1. Check if current_user can add review
            if ($user_can_review == false) {
                // return 'Failed: User cannot submit reviews';
                return 0;
            }

            $required_attachment_validation = $this->need_of_attachments_validation($props);

            if($required_attachment_validation){
                // validation for sumbmitting review attachments
                do_action('scr_photo_reviews/validate_attachments', $props);
            }
            
            // 2. Proceed only in $user_can_review == true . Store new comment.
            $user = get_user_by('id', get_current_user_id());
            $comment_data = $this->build_and_get_comment_data($user, $props);
            $comment_id = wp_new_comment($comment_data);

            // 3. Store wp_comment_consent in Cookies for non-logged-in users
            $this->set_wp_comment_cookies($props, $comment_id);

            // 4. Check if we need to manually approve this review
            $status = ($can_approve) ? 1 : 0;
            wp_set_comment_status($comment_id, $status);

            // 5. Does this review have comment_meta to be updated
            $should_update_comment_meta = (isset($comment_id) && !empty($comment_id) && !isset($props['review_reply']) && $props['parent'] == 0);
            if ($should_update_comment_meta) {
                add_comment_meta($comment_id, SCR_COMMENT_META, $props);

                do_action('scr_woocommerce_integration/add_rating_meta', $comment_id, $props);
                do_action('scr_woocommerce_integration/add_verified_owners_meta', $comment_id);

                do_action('scr_photo_reviews/add_attachments', $comment_id);
            }

            return $comment_id;

        }

        public function build_and_get_comment_data($user, $props)
        {
            $is_user_logged_in = $this->current_user->is_loggedin();

            $comment_data = [];

            // General Properties
            $comment_data['comment_author_IP'] = $this->current_user->get_user_IP();
            $comment_data['comment_post_ID'] = $props['post_id'];
            $comment_data['comment_content'] = $props['description'];
            $comment_data['comment_agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
            $comment_data['comment_type'] = SCR_COMMENT_TYPE;
            // $comment_data['comment_date'] = current_time('timestamp', true);
            $comment_data['comment_parent'] = !isset($props['parent']) ? 0 : $props['parent'];
            $comment_data['comment_approved'] = 1;

            // Properties which change for different user types (logged_in and non_logged_in)
            if ($is_user_logged_in) {
                $user = get_user_by('id', get_current_user_id());
                $comment_data['comment_author'] = $user->display_name;
                $comment_data['comment_author_email'] = $user->user_email;
                $comment_data['comment_author_url'] = $user->user_url;
                $comment_data['user_id'] = $user->ID;
            } else {
                $user = $this->get_non_logged_in_user($props);
                $comment_data['comment_author'] = $user->comment_author;
                $comment_data['comment_author_email'] = $user->comment_author_email;
                $comment_data['comment_author_url'] = $user->comment_author_url;
                $comment_data['user_id'] = 0;
            }

            return $comment_data;
        }

        public function update($props)
        {
            $can_approve = $this->current_user->can_user_directly_publish_reviews();
            $comment_id = $props['comment_id'];

            $required_attachment_validation = $this->need_of_attachments_validation($props);
            
            if($required_attachment_validation){
                // validation for sumbmitting review attachments
                do_action('scr_photo_reviews/validate_attachments', $props);
            }
            
            $user = $this->get_non_logged_in_user($props);

            $commenter_name = $user->comment_author;
            $commenter_email = $user->comment_author_email;
            $commenter_website = $user->comment_author_url;

            if ($this->current_user->is_loggedin()) {
                $user = get_user_by('id', get_current_user_id());
                $commenter_name = $user->display_name;
                $commenter_email = $user->user_email;
                $commenter_website = $user->user_url;
            }

            $comment = array(
                'comment_ID' => $props['comment_id'],
                'comment_author' => $commenter_name,
                'comment_author_email' => $commenter_email,
                'comment_author_url' => $commenter_website,
                'comment_content' => $props['description'],
                'comment_parent' => $props['parent'],
                'comment_approved' => $can_approve ? 1 : 0,
            );

            wp_update_comment($comment);

            $this->set_wp_comment_cookies($props, $props['comment_id']);

            // review only not reply update
            if ($props['parent'] == 0) {

                $data = get_comment_meta($comment_id, SCR_COMMENT_META, true);

                $votes = isset($data['votes']) && !empty($data['votes']) ? $data['votes'] : [];
                $props['votes'] = $votes;

                $attachments = isset($data['attachments']) && !empty($data['attachments']) ? $data['attachments'] : [];
                $props['attachments'] = $attachments;

                unset($props['parent']);
                unset($props['methodType']);

                update_comment_meta($comment_id, SCR_COMMENT_META, $props);

                do_action('scr_photo_reviews/add_attachments', $comment_id);

                do_action('scr_woocommerce_integration/add_rating_meta', $comment_id, $props);
                do_action('scr_woocommerce_integration/add_verified_owners_meta', $comment_id);
            }

            return $comment_id;
        }

        public function store_vote($props)
        {
            $meta_props = get_comment_meta($props['comment_id'], SCR_COMMENT_META, true);

            if (isset($meta_props['votes']) && !empty($meta_props['votes'])) {
                $is_current_user_voted = false;
                foreach ($meta_props['votes'] as &$vote) {
                    if ($vote['user_id'] == $props['vote']['user_id']) {
                        $vote['vote'] = $props['vote']['vote'];
                        $is_current_user_voted = true;
                    }
                    // error_log('each vote : ' . print_r($vote, true));
                }
                if ($is_current_user_voted == false) {
                    array_push($meta_props['votes'], $props['vote']);
                }
            } else {
                $vote_props = ['votes' => [$props['vote']]];
                $meta_props = isset($meta_props) && !empty($meta_props) ? array_merge($meta_props, $vote_props) : $vote_props;
            }
            update_comment_meta($props['comment_id'], SCR_COMMENT_META, $meta_props);
        }

        public function set_wp_comment_cookies($props, $comment_id)
        {
            $is_non_logged_in_user = !$this->current_user->is_loggedin() ? true : false;
            $can_store_wp_consent = isset($props['wp-comment-cookies-consent']) && $props['wp-comment-cookies-consent'] == 'yes' ? true : false;

            // Store wp_comment_consent in Cookies for non-logged-in users
            if ($is_non_logged_in_user && $can_store_wp_consent) {
                $wp_comment = get_comment($comment_id);
                $wp_user = wp_get_current_user();
                $wp_consent = $props['wp-comment-cookies-consent'];

                do_action('set_comment_cookies', $wp_comment, $wp_user, $wp_consent);
            }
        }

        public function get_non_logged_in_user($props)
        {
            $commenter = wp_get_current_commenter();

            $commenter_name = (isset($props['name']) && !empty($props['name'])) ? $props['name'] : $commenter['comment_author'];
            $commenter_email = (isset($props['email']) && !empty($props['email'])) ? $props['email'] : $commenter['comment_author_email'];
            $commenter_website = (isset($props['website']) && !empty($props['website'])) ? $props['website'] : $commenter['comment_author_url'];

            $user = new \stdClass();
            $user->comment_author = $commenter_name;
            $user->comment_author_email = $commenter_email;
            $user->comment_author_url = $commenter_website;

            return $user;
        }

        public function get_processed_data()
        {
            $props = ['parent' => 0];

            if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
                $props['post_id'] = intval($_POST['post_id']);
            }

            if (isset($_POST['title']) && !empty($_POST['title'])) {
                $props['title'] = sanitize_text_field($_POST['title']);
            }

            if (isset($_POST['description']) && !empty($_POST['description'])) {
                $props['description'] = sanitize_textarea_field($_POST['description']);
            }

            if (isset($_POST['pros']) && !empty($_POST['pros'])) {
                $props['pros'] = $this->get_prosandcons(wp_unslash($_POST['pros']));
            }

            if (isset($_POST['cons']) && !empty($_POST['cons'])) {
                $props['cons'] = $this->get_prosandcons(wp_unslash($_POST['cons']));
            }

            if (isset($_POST['scores']) && !empty($_POST['scores'])) {
                $scores = $this->sanitize_array(wp_unslash($_POST['scores']));
                $props['rating'] = $this->get_rating($scores);
                $props['stats'] = $this->get_stat($scores);
            }

            if (isset($_POST['parent']) && !empty($_POST['parent'])) {
                $props['parent'] = intval($_POST['parent']);
            }

            if (isset($_POST['comment_id']) && !empty($_POST['comment_id'])) {
                $props['comment_id'] = intval($_POST['comment_id']);
            }

            if (isset($_POST['methodType']) && !empty($_POST['methodType'])) {
                $props['methodType'] = sanitize_text_field($_POST['methodType']);
            }

            if (isset($_POST['captcha']) && !empty($_POST['captcha'])) {
                $props['captcha'] = sanitize_key($_POST['captcha']);
            }

            if (isset($_POST['attachments']) && !empty($_POST['attachments'])) {
                // stored attachement ID only when its available
                $props['attachments'] = $this->sanitize_array(wp_unslash($_POST['attachments']));
            }

            if(isset($_POST['form_action_type']) && !empty($_POST['form_action_type'])){
                $props['form_action_type'] = sanitize_text_field($_POST['form_action_type']);
            }

            $props = apply_filters('scr_form_process_data', $props);

            return $props;
        }

        public function get_processed_voting_data()
        {
            $data = [];
            if (isset($_POST['comment_id']) && !empty($_POST['comment_id'])) {
                $data['comment_id'] = intval($_POST['comment_id']);
            }

            if (isset($_POST['vote'])) {
                $data['vote'] = [
                    'user_id' => get_current_user_id(),
                    'vote' => intval($_POST['vote']),
                ];
            }

            return $data;
        }

        public function get_prosandcons($features)
        {
            $items = [];

            if (isset($features) && !empty($features)) {
                foreach ($features as $key => $value) {
                    $items[intval($key)] = [
                        'item' => sanitize_text_field($value),
                    ];
                }
            }

            return $items;
        }

        public function get_rating($scores)
        {
            $count = 0;
            $rating = 0;
            $cumulative = 0;

            if (isset($scores)) {
                foreach ($scores as $key => $value) {
                    $cumulative += $value;
                    $count++;
                }

                return $rating = round($cumulative / $count);
            }
            return $rating;
        }

        public function get_stat($scores)
        {
            $stats = [];

            if (isset($scores) && !empty($scores)) {
                foreach ($scores as $key => $value) {
                    $stats[$key] = [
                        'stat_name' => $key,
                        'rating' => $value,
                    ];
                }
            }

            return $stats;
        }

        private function sanitize_array($data)
        {
            $sanitized_data = [];
            if (is_array($data)) {
                foreach ($data as $array_key => $value) {
                    $sanitized_data[$array_key] = intval($value);
                }
                return $sanitized_data;
            }

            return $data;
        }

        public function need_of_attachments_validation($props){
            if(isset($props['form_action_type']) && $props['form_action_type'] == 'reply'){
                return false;
            }
            return true;
        }
    }
    // END CLASS
}