<?php

namespace StarcatReview\App\Abstracts;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Abstracts\Collection')) {
    abstract class Collection
    {
        public function __construct()
        {
            $this->utils = new \StarcatReview\Includes\Utils();
        }

    } // END CLASS

}