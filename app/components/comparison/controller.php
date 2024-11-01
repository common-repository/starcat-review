<?php

namespace StarcatReview\App\Components\Comparison;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Comparison\Controller')) {
    class Controller
    {
        public function __construct()
        {
            $this->model = new \StarcatReview\App\Components\Comparison\Model();
            $this->view = new \StarcatReview\App\Components\Comparison\View();
        }

        public function get_view($args)
        {

            $stats = $this->model->get($args);
            error_log('ct controller : ' . print_r($stats, true));

            return $this->view->get_html($stats);
        }

        public function get_scr_details($search_key)
        {
            // echo $search_key;
            $search_post_data = [];
            $search_post_data = array('Item1', 'Item2', 'Item3', 'Item4', 'Item5', 'Item6', 'Item7');
            $data = array('status' => '1', 'data' => $search_post_data);
            echo json_encode($data, true);
            wp_die();
        }
    } // END CLASS

}
