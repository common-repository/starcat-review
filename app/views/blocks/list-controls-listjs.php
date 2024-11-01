<?php

namespace StarcatReview\App\Views\Blocks;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Blocks\List_Controls_Listjs')) {
    class List_Controls_Listjs
    {

        public function __construct()
        {
        }

        /* HTML for List_Controls */
        public function get_view()
        {

            $html = '';

            $html .= '<div class="scr-controls-container scr-container">';
            $html .= '<input class="collection-search" placeholder="' . __('Search', SCR_DOMAIN) . '" />';
            $html .= '<ul class="sort-by">';
            $html .= '<li class="sort btn" data-sort="review-card__header">' . __('Sort by name', SCR_DOMAIN) . '</li>';
            $html .= '</ul>';
            $html .= "</div>";

            return $html;
        }

        public function search()
        {
            $html = '';
            $html .= '<input class="collection-search scr-search" placeholder="' . __('Search', SCR_DOMAIN) . '" />';

            return $html;
        }

        public function sort_button()
        {
            $html = '';
            $html .= '<ul class="sort-by">';
            $html .= '<li class="sort btn" data-sort="review-card__header">' . __('Sort by name', SCR_DOMAIN) . '</li>';
            $html .= '</ul>';
            return $html;
        }
    } // END CLASS
}
