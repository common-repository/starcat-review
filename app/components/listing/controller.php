<?php

namespace StarcatReview\App\Components\Listing;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Listing\Controller')) {
    class Controller
    {
        public function __construct()
        {
            $this->model = new \StarcatReview\App\Components\Listing\Model();
            $this->view = new \StarcatReview\App\Components\Listing\View();
        }

        public function get_view($args)
        {
            $viewProps = $this->model->get_viewProps($args);
            return $this->view->get_html($viewProps);
        }
    } // END CLASS

}
