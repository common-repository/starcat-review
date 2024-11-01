<?php

namespace StarcatReview\App\Widget_Makers\Comparison;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Widget_Makers\Comparison\Widget')) {
    class Widget
    {

        public function load()
        {
            // Shortcode
            // add_shortcode('starcat_review_list', array($this, 'reviews_list'));

            // WordPress Widget
            // add_action('widgets_init', [$this, 'register_widget']);

            // Elementor Widget
            add_action('elementor/widgets/widgets_registered', [$this, 'register_elementor_widget']);
        }

        public function get_view($args)
        {
            error_log('shortcodes args' . print_r($args, true));
            $default_args = $this->get_default_args();
            $component_args = array_merge($default_args, $args);
            $component_args = $this->get_interpreted_args($component_args);
            $component_args = $this->boolean_conversion($component_args);

            // $post_ids = [176, 174, 147];

            // $args = array(
            //     'post__in' => $post_ids,
            //     'post_type' => SCR_POST_TYPE,
            // );

            // $posts = get_posts($args);

            // $get_post_props = $this->get_default_post_props($posts);

            //view type (its comes to static/dynamic)
            // $ct_args = array(
            //     'posts' => count($get_post_props) ? $get_post_props : [],
            //     'view_type' => 'dynamic'
            // );
            $comparison_controller = new \StarcatReview\App\Components\Comparison\Controller();
            // error_log($component_args);
            return $comparison_controller->get_view($component_args);
        }

        protected function get_interpreted_args($args)
        {
            $posts = $this->get_default_posts($args);
            // error_log("args" . print_r($args, true));
            $component_args['posts'] = $this->get_default_post_props($args, $posts);
            $component_args['title'] = $args['title'];
            $component_args['max_num_of_items'] = $args['max_num_of_items'];
            $component_args['show_type'] = $args['show_type'];

            error_log('component_args' . print_r($component_args, true));
            return $component_args;
        }

        protected function get_default_posts($args)
        {
            // error_log('defaultposts' . print_r($args, true));
            $posts = [];
            $num_of_columns = isset($args['num_of_cols']) ? $args['num_of_cols'] : 3;

            $get_posts = explode(',', $args['posts']);

            if (in_array('random', $get_posts)) {

                $post_args = array(
                    'post_type' => array(SCR_POST_TYPE),
                    'post_status' => array('publish'),
                    'nopaging' => true,
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                    'posts_per_page' => $num_of_columns,
                );
                $results = new \WP_Query($post_args);

                if ($results->have_posts()) {
                    foreach ($results->posts as $post) {
                        $posts[] = $post;
                    }
                }
            } else {

                $post_args = array(
                    'post__in' => $get_posts,
                    'post_type' => array(SCR_POST_TYPE),
                    'posts_per_page' => $num_of_columns,
                );

                $posts = get_posts($post_args);
            }

            return $posts;
        }

        protected function get_default_post_props($args, $posts)
        {
            $default_posts = array();

            if (isset($posts)) {
                foreach ($posts as $post) {
                    $post_infos = array();
                    $post_infos['ID'] = $post->ID;
                    $post_infos['title'] = $post->post_title;
                    $post_featured_image = get_the_post_thumbnail_url($post->ID);
                    $post_infos['featured_image_url'] = $post_featured_image != "" ? $post_featured_image : SCR_URL . 'includes/assets/img/dummy-review.jpg';
                    $post_infos['url'] = get_post_permalink($post->ID);
                    // get review ratings from function.php
                    $get_scr_get_user_reviews = scr_get_user_reviews($post->ID);

                    // get post stat list
                    $get_stat_list = $this->get_default_stat_list($get_scr_get_user_reviews);

                    // get post overall reatings
                    $get_overall_stats = scr_get_overall_rating($post->ID);
                    $post_infos['stats'] = $get_stat_list;
                    $post_infos['overall_ratings'] = $get_overall_stats;
                    $default_posts[$post->ID] = $post_infos;
                }
            }

            return $default_posts;
        }

        protected function get_default_stat_list($args)
        {
            $stats = array();
            if (count($args) > 0) {
                foreach ($args as $post_review) {
                    $reviews = isset($post_review->reviews) ? $post_review->reviews : [];
                    if (isset($reviews) && count($reviews) > 0) {
                        $stats = $reviews['stats'];
                    }
                }
            }
            return $stats;
        }

        protected function get_collection_props($args)
        {}

        protected function execute_methods_with_queries($args)
        {
            // $this->posts = $this->cat_posts_repo->get_category_posts($args);
        }

        /* PROTECTED METHODS */
        protected function boolean_conversion($args)
        {
            foreach ($args as $key => $arg) {

                if ($arg == 'on') {
                    $args[$key] = true;
                } else if ($arg == 'off') {
                    $args[$key] = false;
                }
            }

            return $args;
        }

        public function get_default_args()
        {
            $args = array();

            // Get Default Values from GET - FIELDS
            $fields = $this->get_fields();
            foreach ($fields as $key => $field) {
                $args[$key] = $field['default'];
            }

            return $args;
        }

        public function get_fields()
        {

            $get_src_posts_options = $this->get_src_posts(true);

            $fields = array(
                'title' => [
                    'name' => 'title',
                    'label' => __('Title', 'starcat-review'),
                    'default' => 'Title of Comparison Table',
                    'type' => 'text',
                ],
                'max_num_of_items' => [
                    'name' => 'num_of_cols',
                    'label' => __('Maximum Number of Items', 'starcat-review'),
                    'default' => 'two',
                    'options' => array(
                        '1' => __('1', 'starcat-review'),
                        '2' => __('2', 'starcat-review'),
                        '3' => __('3', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],
                'show_type' => [
                    'name' => 'show_type',
                    'label' => __('Show Compate Table Type', 'starcat-review'),
                    'default' => 'static',
                    'options' => array(
                        'static' => __('Static', 'starcat-review'),
                        'dynamic' => __('Dynamic', 'starcat-review'),
                    ),
                    'type' => 'select',
                ],
                'posts' => [
                    'name' => 'posts',
                    'label' => __('Choose Values', 'starcat-review'),
                    'default' => 'random',
                    'options' => $get_src_posts_options,
                    'type' => 'multi-select',
                ],
            );

            return $fields;
        }

        public function get_src_posts()
        {
            $post_options = array();
            $args = array(
                'post_type' => array(SCR_POST_TYPE),
                'post_status' => array('publish'),
                'nopaging' => true,
                'order' => 'ASC',
                'orderby' => 'menu_order',
            );
            $results = new \WP_Query($args);

            if ($results->have_posts()) {
                $post_options['random'] = 'Random';
                foreach ($results->posts as $post) {
                    $post_options[$post->ID] = substr(wp_strip_all_tags($post->post_title), 0, 25) . '...';
                    // $post_options[$post->ID] = $post->post_title;
                }
            }

            return $post_options;
        }

        public function get_style_config()
        {}
    } // END CLASS
}
