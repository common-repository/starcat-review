<?php

namespace StarcatReview\App\Components\Summary;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Summary\Controller')) {
    class Controller
    {
        public function __construct()
        {
            $this->model = new \StarcatReview\App\Components\Summary\Model();
            $this->view = new \StarcatReview\App\Components\Summary\View();
        }

        public function get_view(array $args, array $user_args = array())
        {
            $props = $this->model->get_viewProps($args,$user_args);
            return $this->view->get($props);
        }
    }
}