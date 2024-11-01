<?php

namespace StarcatReview\App\Widget_Makers\Review_Listing;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Widget_Makers\Review_Listing\Fields_Model')) {
    class Fields_Model
    {
        public function __construct()
        {
            // $this->repo = new \StarcatReview\Includes\Repos\Faq_Repo();
        }



        public function get_fields()
        {
            // $options = $this->repo->get_options('categories');
            $categories_options = $this->get_category_options(true);  // $show_all = true

            $fields = array(
                'title' => [
                    'name' => 'title',
                    'label' => __('Title', 'starcat-review'),
                    'default' => 'Title of Listing',
                    'type' => 'text',
                ],
                'show_controls' => [
                    'name' => 'show_controls',
                    'label' => __('Show Controls', 'starcat-review'),
                    'default' => 'on',
                    'options' => array(
                        'on' => __('On', 'starcat-review'),
                        'off' => __('Off', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],
                'show_search' => [
                    'name' => 'show_search',
                    'label' => __('Show Search', 'starcat-review'),
                    'default' => 'on',
                    'options' => array(
                        'on' => __('On', 'starcat-review'),
                        'off' => __('Off', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],
                'show_sortBy' => [
                    'name' => 'show_sortBy',
                    'label' => __('Show SortBy', 'starcat-review'),
                    'default' => 'on',
                    'options' => array(
                        'on' => __('On', 'starcat-review'),
                        'off' => __('Off', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],
                'show_num_of_reviews_filter' => [
                    'name' => 'show_num_of_reviews_filter',
                    'label' => __('Show Number of Reviews Filter', 'starcat-review'),
                    'default' => 'on',
                    'options' => array(
                        'on' => __('On', 'starcat-review'),
                        'off' => __('Off', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],

                'default_sortBy' => [
                    'name' => 'default_sortBy',
                    'label' => __('Default SortBy', 'starcat-review'),
                    'default' => 'on',
                    'options' => array(
                        'on' => __('On', 'starcat-review'),
                        'off' => __('Off', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],

                'num_of_cols' => [
                    'name' => 'num_of_cols',
                    'label' => __('Number of Columns', 'starcat-review'),
                    'default' => '2',
                    'options' => array(
                        '1' => __('1', 'starcat-review'),
                        '2' => __('2', 'starcat-review'),
                        '3' => __('3', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],

                'categories' => [
                    'name' => 'categories',
                    'label' => __('Categories', 'starcat-review'),
                    'default' => 'all',
                    'options' => $categories_options,
                    'type' => 'multi-select',
                ],
                // 'theme' => [
                //     'name' => 'theme',
                //     'label' => __('Theme', 'starcat-review'),
                //     'default' => 'light',
                //     'options' => array(
                //         'light' => __('Light', 'starcat-review'),
                //         'dark' => __('Dark', 'starcat-review'),
                //     ),
                //     'type' => 'select',
                // ],
            );


            return $fields;
        }

        public function get_category_options($show_all = false)
        {
            $category_options = [];
            $terms = get_terms(SCR_CATEGORY, array(
                'hide_empty' => false,
            ));

            foreach ($terms as $term) {
                if (!isset($term->term_id)) {
                    continue;
                }
                $category_options[$term->term_id] = $term->name;
            }

            if ($show_all == true) {
                $category_options = array('all' => 'All') + $category_options;
            }
            // error_log('Visible Terms : ' . print_r($category_options, true));

            return $category_options;
        }




        // OTHER
    } // END CLASS
}