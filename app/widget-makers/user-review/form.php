<?php

namespace StarcatReview\App\Widget_Makers\User_Review;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Widget_Makers\User_Review\Form')) {
    class Form
    {
        public $limit = 0;

        public function get_form(array $args, array $user_args = array())
        {
            $this->set_limit();
            $limit = $this->get_limit();
            $use_of_form = isset($user_args['use_of_form']) ? $user_args['use_of_form'] : '';

            if (!empty($use_of_form) && $use_of_form == 'edit_review') {
                if ($limit > 1) {
                    return;
                }
            }

            $form_controller = new \StarcatReview\App\Components\Form\Controller();
            return $form_controller->get_view($args, $user_args);
        }

        public function set_limit()
        {
            global $SCR_FORM_LIMIT;
            $this->limit = $SCR_FORM_LIMIT + 1;
            $SCR_FORM_LIMIT = $this->limit;
        }

        public function get_limit()
        {
            global $SCR_FORM_LIMIT;
            return empty($SCR_FORM_LIMIT) ? 1 : $SCR_FORM_LIMIT;
        }

    }
}