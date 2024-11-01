<?php

namespace StarcatReview\App\Abstracts;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!interface_exists('\StarcatReview\App\Abstracts\Widget_Model_Interface')) {
    interface  Widget_Model_Interface
    {
        // Must have methods 
        public function get_viewProps($args);
        public function get_default_args();
        public function get_style_config();
        public function get_fields();
    }
}