<?php

namespace StarcatReview\App\Services;

use \StarcatReview\Includes\Settings\SCR_Getter as Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Services\User')) {
    class User
    {
        public function get_user_IP()
        {
            if (!empty($_SERVER['REMOTE_ADDR']) && rest_is_ip_address(wp_unslash($_SERVER['REMOTE_ADDR']))) { // WPCS: input var ok, sanitization ok.
                $IP = wp_unslash($_SERVER['REMOTE_ADDR']); // WPCS: input var ok.
            } else {
                $IP = '127.0.0.1';
            }

            return $IP;
        }

        public function can_user_directly_publish_reviews()
        {
            $can_manage_options = current_user_can('manage_options') ? true : false;
            $approve = (Getter::get('ur_auto_approve')) ? true : $can_manage_options;

            return $approve;
        }

        public function can_review()
        {
            return true;
        }

        public function is_loggedin()
        {
            return is_user_logged_in();
            // return true;
        }
    } // END CLASS
}
