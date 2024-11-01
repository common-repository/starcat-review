<?php

namespace StarcatReview\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Utils')) {
    class Utils
    {
        public function __construct()
        { }

        /* NOTE: Only for Testing and Development Mode */
        public function get_reviews_data()
        {
            $review_data_json = file_get_contents(SCR_PATH . "/tests/_data/review-data.json");
            $post_data = json_decode($review_data_json, true);

            return $post_data;
        }

        /*  ACF Plugin Utils */

        public function get_acf_group()
        {

            return 'value';
        }
    }
} // END CLASS
