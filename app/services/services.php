<?php

namespace StarcatReview\App\Services;

use \StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Services\Services')) {
    class Services
    {
        public function register_services()
        {
            // error_log('!!! register services !!!');

            $stats_factory = new \StarcatReview\App\Services\Stats_Factory();
            $comments_factory = new \StarcatReview\App\Services\Comments_Factory();

            add_filter('scr_stat', [$this, 'get_allowed_stat'], 1, 1);
            add_filter('scr_stat', [$stats_factory, 'get_single_stat']);
            add_filter('scr_stat_args', [$stats_factory, 'get_stat_args'], 10, 2);

            add_filter('scr_comment', [$comments_factory, 'get_comment'], 1, 2);
            add_filter('scr_comment', [$this, 'get_can_edit_comment_capabilities']);

            add_filter('scr_comments_args', [$comments_factory, 'get_comments_args'], 10, 2);
            add_filter('scr_capabilities_args', [$this, 'get_capabilities_args'], 1);
        }

        /*
         *  Get Filtered Stats with Global stats ( Settings )
         */
        public function get_allowed_stat($given_stats = [])
        {
            $stats = [];

            if (empty($given_stats)) {
                return $given_stats;
            }

            // error_log('given_stats : ' . print_r($given_stats, true));

            $global_stats = SCR_Getter::get_global_stats();
            $singularity = SCR_Getter::get_stat_singularity();

            if ($singularity == 'single') {
                $global_stats = [$global_stats[0]];
            }

            foreach ($global_stats as $allowed_stat) {
                $allowed_stat_name = strtolower($allowed_stat['stat_name']);
                $is_stat_exist = array_key_exists($allowed_stat_name, $given_stats);

                if ($is_stat_exist && $given_stats[$allowed_stat_name]['rating'] > 0) {
                    $stats[$allowed_stat_name] = $given_stats[$allowed_stat_name]['rating'];
                }
            }

            return $stats;
        }

        public function get_can_edit_comment_capabilities($comment)
        {
            // return if current_user is not a logged-in-user
            if (isset($comment['user_id']) && $comment['user_id'] == 0) {
                return $comment;
            }

            $comment['can_edit'] = false;
            // Logged-in-users
            if (get_current_user_id() == $comment['user_id']) {
                $comment['can_edit'] = true;
            }

            return $comment;
        }

        public function get_capabilities_args($comments = [])
        {
            $capability = [
                'can_user_vote' => true,
                'can_user_reply' => false,
                'can_user_review' => false,
            ];

            $is_logged_in_user = is_user_logged_in() ? true : false;
            $is_non_logged_in_user = !$is_logged_in_user ? true : false;

            $who_can_review = $this->get_who_can_review();
            $can_same_user_leave_multiple_review = SCR_Getter::get('ur_allow_same_user_can_leave_multiple_reviews');

            $is_logged_in_user_can_review = ($is_logged_in_user && ($who_can_review == ('everyone' || 'logged_in'))) ? true : false;
            $is_non_logged_in_user_can_review = ($is_non_logged_in_user && $who_can_review == 'everyone') ? true : false;

            $is_either_one_of_the_user_can_review = $is_logged_in_user_can_review || $is_non_logged_in_user_can_review ? true : false;

            if ($is_either_one_of_the_user_can_review && $can_same_user_leave_multiple_review || $is_either_one_of_the_user_can_review) {
                $capability['can_user_review'] = true;
            }

            // can reply and vote for a review feature is only for logged-in-users
            if ($is_logged_in_user_can_review) {
                $capability['can_user_reply'] = true;
                // $capability['can_user_vote'] = true;
            }

            if ($can_same_user_leave_multiple_review == false && isset($comments) && !empty($comments)) {
                foreach ($comments as $comment) {
                    // Current user already reviewed
                    $has_current_user_already_reviewed = ($comment['user_id'] == get_current_user_id() && $comment['parent'] == 0);
                    $has_current_user_already_reviewed = apply_filters('scr_has_current_user_already_reviewed', $has_current_user_already_reviewed, $comment);
                    if ($has_current_user_already_reviewed) {
                        $capability['can_user_review'] = false;
                    }
                }
            }

            // error_log('is_logged_in_user_can_review : ' . $is_logged_in_user_can_review);
            // error_log('is_non_logged_in_user_can_review : ' . $is_non_logged_in_user_can_review);
            // error_log('is_either_one_of_the_user_can_review : ' . $is_either_one_of_the_user_can_review);
            // error_log('can_same_user_leave_multiple_review : ' . $can_same_user_leave_multiple_review);

            // error_log('capability : ' . print_r($capability, true));

            return $capability;
        }

        public function get_who_can_review(){
            $who_can_review = SCR_Getter::get('ur_who_can_review');
            if(SCR_Getter::is_single_product_post()){
                $who_can_review = SCR_Getter::get('woo_ur_who_can_review');
            }
            return $who_can_review;
        }

    } // END CLASS
}
