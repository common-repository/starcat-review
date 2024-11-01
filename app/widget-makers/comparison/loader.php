<?php

namespace StarcatReview\App\Widget_Makers\Comparison;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Widget_Makers\Comparison\Loader')) {
    class Loader
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

        public function register_widget()
        {

            // error_log(' Comparison Table register_widget: ');
            $widget_args = $this->get_widget_args();

            require_once SCR_PATH . '/includes/lib/widgetry/widget-factory.php';
            $scr_widget = new \Widgetry\Widget_Factory($widget_args);
            register_widget($scr_widget);
        }

        public function register_elementor_widget()
        {
            $args = $this->get_widget_args();
            $elementor_args = $this->get_elementor_args($args);


            require_once SCR_PATH . '/includes/lib/widgetry/elementor-widget-factory.php';
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $elementor_args));
        }

        public function get_widget_args()
        {
            $args = [
                'id' => 'starcat-review-comparison-table',
                'name' => 'Helpie Comparison Table',
                'description' => 'Comparison Table Widget',
                'icon' => 'fa fa-th-list', // Used by Elementor only
                'categories' => ['general-elements'], // Used by Elementor only
                'model' =>  new \StarcatReview\App\Widget_Makers\Comparison\Widget(),
                'view' => new \StarcatReview\App\Widget_Makers\Comparison\Widget(),
            ];

            return $args;
        }

        public function get_elementor_args($args)
        {
            $elementor_args = [];

            // Different key
            $elementor_args['name'] = $args['id'];
            $elementor_args['title'] = $args['name'];
            $elementor_args['icon'] = $args['icon'];

            // Same key
            $elementor_args['categories'] = $args['categories'];
            $elementor_args['model'] = $args['model'];
            $elementor_args['view'] = $args['view'];

            return $elementor_args;
        }


        public function get_view()
        {
            $comparison_controller = new \StarcatReview\App\Components\Comparison\Controller();
            $post_ids = [];
            return $comparison_controller->get_view($post_ids);
        }

        protected function get_collection_props($args)
        { }

        protected function execute_methods_with_queries($args)
        {
            // $this->posts = $this->cat_posts_repo->get_category_posts($args);
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
            );


            return $fields;
        }

        public function get_style_config()
        { }
    } // END CLASS
}