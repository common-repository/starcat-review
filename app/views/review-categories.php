<?php

namespace StarcatReview\App\Views;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Review_Categories')) {
    class Review_Categories
    {

        public function __construct()
        {
            $this->blocks = new \StarcatReview\App\Views\Blocks();
        }

        public function get_view($terms)
        {
            $itemsProps = [];
            $collectionProps = ['no_of_cols' => 3];
            $ii = 0;

            // Convert $terms to $itemsProps
            foreach ($terms as $key => $term) {

                $itemsProps[$ii] = [
                    'title' => $term->name,
                    'content' => $term->description,
                    'url' => get_term_link($term)
                ];

                $ii++;
            }

            $html = $this->blocks->collection->get_view($itemsProps, $collectionProps);

            return $html;
        }
    } // END CLASS
}