<?php

namespace StarcatReview\App\Views;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Blocks')) {
    class Blocks
    {
        public function __construct()
        {
            $this->collection = new \StarcatReview\App\Views\Blocks\Simple_Collection();
        }
    } // END CLASS
}