<?php

namespace StarcatReview\Includes\Utils;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Utils\Users')) {
    class Users
    {

        public function create_new_user($role = 'subscriber', $username = 'subman', $password = 'subpass', $email = 'submail@pauple.com')
        {
            $user_id = wp_create_user($username, $password, $email);
            $userdata = array('ID' => $user_id, 'role' => $role);
            wp_update_user($userdata);
            error_log('create_new_user: ' . $role);

            return $user_id;
        }

    } // END CLASS

}
