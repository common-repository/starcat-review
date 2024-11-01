<?php

namespace StarcatReview\App\Components\ProsAndCons;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\ProsAndCons\Controller')) {
    class Controller
    {
        public function __construct()
        {
            $this->model = new \StarcatReview\App\Components\ProsAndCons\Model();
            $this->view = new \StarcatReview\App\Components\ProsAndCons\View();
        }

        public function get_view($args)
        {
            $props = $this->model->get_viewProps($args);
            return $this->view->get($props);
        }

        public function get_fields($args)
        {
            $props = $this->model->get_viewProps($args);
            // error_log('args : ' . print_r($args, true));

            return $this->view->get_fields($props);

        }
    } // END CLASS

}

// options
// data
