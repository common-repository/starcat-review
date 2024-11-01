<?php

namespace StarcatReview\App\Components\Breadcrumbs;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Breadcrumbs')) {
    class View
    {
        public function __construct()
        { }

        public function single_item($link, $title)
        {
            // echo 'link' . $link;
            // echo 'title' . $title;
            $html = '';
            $html .= "<a class='mainpage-link' href='" . $link . "'>" . $title . '</a> ';
            return $html;
        }

        public function get_seperator()
        {
            $html = '';
            $html .= "<span class='scr_separator'> &nbsp; <i class='angle right icon'></i>&nbsp;</span>";
            return $html;
        }
    }
}
