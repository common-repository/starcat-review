<?php

namespace StarcatReview\App\Components\Breadcrumbs;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Breadcrumbs\Controller')) {
    class Controller
    {
        private $model;

        public function __construct()
        {
            $this->model = new \StarcatReview\App\Components\Breadcrumbs\Model();
            $this->view = new \StarcatReview\App\Components\Breadcrumbs\View();
        }

        public function get_view()
        {
            $html = '';
            // $html = '<p>Bread-crumb Initial Setup ..</p>';
            $post_id = get_the_ID();
            $page = is_archive() ? 'archive' : 'single';

            // $html .= '<li>' . $post_id . '</li>';
            // $html .= '<li>' . $page . '</li>';
            $breadcrumb_info = $this->model->get_scr_info($post_id, $page);
            // echo '<pre>';
            // print_r($breadcrumb_info);
            // echo '</pre>';
            // exit;
            $order = ['post_type', 'parent_term', 'term', 'post'];
            $html .= "<div class='breadcrumbs scr-breadcrumbs'>";
            for ($ii = 0; $ii < sizeof($breadcrumb_info); $ii++) {
                $key = $order[$ii];
                if (isset($breadcrumb_info[$key]) && !empty($breadcrumb_info[$key])) {

                    if ($ii != 0) {
                        $html .= $this->view->get_seperator();
                    }

                    $html .= $this->view->single_item($breadcrumb_info[$key]['permalink'], $breadcrumb_info[$key]['title']);
                }
            }
            $html .= '</div>';

            return $html;
        }
    }
}
