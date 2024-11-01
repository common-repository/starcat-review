<?php

namespace StarcatReview\App\Views\Rating_Types;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Rating_Types\Circle_Rating')) {
    class Circle_Rating
    {
        public function __construct($var)
        {
            $this->var = $var;
        }

        public function get_html()
        {
            return '';
        }
    } // END CLASS
}
