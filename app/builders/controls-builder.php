<?php

namespace StarcatReview\App\Builders;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Builders\Controls_Builder')) {
    class Controls_Builder
    {

        public function __construct($type = '')
        {
            $sort_options = [
                'post-date' => __('Recent', SCR_DOMAIN),
                'avg-rating' => __('Most Positive', SCR_DOMAIN),
                'trending' => __('Trending', SCR_DOMAIN),
                'alphabet-asc' => __('Alphabetic Asc', SCR_DOMAIN),
                'alphabet-desc' => __('Alphabetic Desc', SCR_DOMAIN),
                'review-count' => __('Number of Reviews', SCR_DOMAIN),
                'post-modified' => __('Recently Updated', SCR_DOMAIN),
            ];

            if ($type == 'user_review') {
                $sort_options = [
                    'post-date' => __('Recent', SCR_DOMAIN),
                    'avg-rating' => __('Most Positive', SCR_DOMAIN),
                    'helpful' => __('Most Helpful', SCR_DOMAIN),
                ];
            }
            $this->controls = [
                'search' => [
                    'type' => 'search',
                    'size' => 12,
                ],

                'sort' => [
                    'name' => 'sort',
                    'type' => 'dropdown',
                    'default' => 'post-date',
                    'label' => __('Sort by', SCR_DOMAIN),
                    'options' => $sort_options,
                    'size' => 4,
                ],
                // 'reviews' => [
                //     'name' => 'reviews',
                //     'type' => 'dropdown',
                //     'default' => '',
                //     'label' => 'No. of reviews',
                //     'options' => [
                //         '2' => '2+',
                //         '4' => '4+',
                //         '25' => '25+',
                //         '50' => '50+'
                //     ],
                //     'size' => 4
                // ],
                // 'verified' => [
                //     'name' => 'verified',
                //     'type' => 'radio',
                //     'label' => 'Verified',
                //     'default' => 'either',
                //     'options' => [
                //         'true' => 'True',
                //         'false' => 'False',
                //         'either' => 'Either'
                //     ],
                //     'size' => 4
                // ]
            ];

            $this->semantic = new \StarcatReview\App\Views\Blocks\List_Controls_Semantic();
            $this->listjs = new \StarcatReview\App\Views\Blocks\List_Controls_Listjs();
        }

        public function get_controls($show_controls)
        {
            $html = '';

            $html .= '<div class="scr-controls">';
            $html .= '<div class="row">';

            foreach ($this->controls as $key => $control) {

                // error_log('$key : ' . $key);
                // error_log('$control : ' . print_r($control, true));
                if (!$show_controls[$key]) {
                    continue;
                }
                // skip iteration if false

                $map = $this->get_map($control['type']);
                $methodName = $map['methodName'];

                $html .= '<div class="item col-xs-12 col-lg-' . $control['size'] . '">';
                $html .= $map['class']->$methodName($control);
                $html .= '</div>';
            }

            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }

        private function get_map($controlName)
        {
            $map = [
                'search' => [
                    'class' => new \StarcatReview\App\Views\Blocks\List_Controls_Listjs(),
                    'methodName' => 'search',
                ],
                'sort_button' => [
                    'class' => new \StarcatReview\App\Views\Blocks\List_Controls_Listjs(),
                    'methodName' => 'sort_button',
                ],
                'dropdown' => [
                    'class' => new \StarcatReview\App\Views\Blocks\List_Controls_Semantic(),
                    'methodName' => 'dropdown',
                ],
                'radio' => [
                    'class' => new \StarcatReview\App\Views\Blocks\List_Controls_Semantic(),
                    'methodName' => 'radio_group',
                ],
            ];

            return $map[$controlName];
        }
    } // END CLASS

}
