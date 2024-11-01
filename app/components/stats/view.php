<?php

namespace StarcatReview\App\Components\Stats;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Stats\View')) {
    class View
    {
        public function __construct($viewProps)
        {
            $this->viewProps = $viewProps;
        }

        public function get()
        {
            $viewProps = $this->viewProps;

            switch ($viewProps['collection']['type']) {
                case "star":
                    $this->star_rating = new \StarcatReview\App\Views\Rating_Types\Star_Rating($viewProps);
                    return $this->star_rating->get_view();
                case "bar":
                    $this->bar_rating = new \StarcatReview\App\Views\Rating_Types\Bar_Rating($viewProps);
                    return $this->bar_rating->get_view();
                case "circle":
                    $this->circle_rating = new \StarcatReview\App\Views\Rating_Types\Circle_Rating($viewProps);
                    return $this->circle_rating->get_html();
                default:
                    $this->star_rating = new \StarcatReview\App\Views\Rating_Types\Star_Rating($viewProps);
                    return $this->star_rating->get_view();
            }
        }
    }
}
