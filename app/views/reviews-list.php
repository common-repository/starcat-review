<?php

namespace StarcatReview\App\Views;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Reviews_List')) {
    class Reviews_List
    {
        private $html;

        public function __construct()
        {
            // $this->model = $reviews;
        }

        public function get()
        {
            $html = "<h1>User Review List" . $this->model->title . "</h1>";
            $html .= $this->get_list();
            $this->html = $html;
            return $this->html;
        }

        public function get_list()
        {
            $html = '';

            $html .= '<div id="lovely-things-list">';
            $html .= $this->get_list_header();
            $html .= '<ul class="list scr-list">';
            $html .= $this->get_items();
            $html .= '</ul>';
            $html .= ' <ul class="pagination"></ul>';
            $html .= '</div>';

            // A bunch of items

            return $html;
        }

        public function get_list_header()
        {
            $html = '';

            $html .= '<input class="search" placeholder="Search" />';

            $html .= '<ul class="sort-by">';
            $html .= '<li class="sort btn" data-sort="title">Sort by name</li>';
            $html .= '</ul>';

            // $html .= '<ul class="filter">';
            // $html .= '<li class="btn" id="filter-none">Show all</li>';
            // $html .= '<li class="btn" id="filter-games">Only show games</li>';
            // $html .= '<li class="btn" id="filter-beverages">Only show beverages</li>';
            // $html .= '</ul>';

            return $html;
        }

        public function get_items()
        {
            $size = 5;

            $html = '';
            for ($ii = 0; $ii < $size; $ii++) {
                $html .= $this->get_single_item($ii);
            }
            return $html;
        }

        public function get_single_item($ii)
        {

            $number = $this->get_number($ii);
            $html = '';

            $html .= '<li>';
            // $html .= '<img src="https://ununsplash.imgix.net/photo-1414788020357-3690cfdab669?q=75&fm=jpg&s=da7d3842604f06bf5c6ded7f4fe7aeed" />';
            $html .= '<h3 class="title">Blog Post ' . $number . '</h3>';
            $html .= '<p class="content">Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio.</p>';
            $html .= '<div class="button">Read More</div>';
            $html .= '</li>';

            return $html;
        }

        protected function get_number($num)
        {
            $number_words = array('zero', 'one', 'two', 'three', 'four', 'five');

            return $number_words[$num];
        }
    } // END CLASS
}
