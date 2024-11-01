<?php

namespace StarcatReview\App\Abstracts;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Abstracts\Review')) {
    abstract class Review
    {
        public function __construct($post)
        {
            $this->map_post_props();
            $this->define_computed_props();
        }

        public function define_computed_props()
        {
            $this->set_overall_rating();
            $this->set_overall_pros_and_cons();
        }

    } // END CLASS

}