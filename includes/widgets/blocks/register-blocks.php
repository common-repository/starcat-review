<?php

namespace StarcatReview\Includes\Widgets\Blocks;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Widgets\Blocks\Register_Blocks')) {
    class Register_Blocks
    {
        public function __construct($fields, $style_config)
        {
            // error_log('load fields: ' . print_r($fields, true) );
            $this->fields = $fields;
            $this->fields['style'] = array();
            $this->style_config = $style_config;
            $this->get_element_config($style_config);
        }

        public function get_element_config($style_config)
        {

            foreach ($style_config as $key => $element) {
                # code...
                $name = $element['name'];

                // $styleProps = $element['styleProps'];

                if (isset($element['styleProps'])) {
                    $this->fields['style'][$name] = $element;
                }

                if (isset($element['children'])) {
                    $this->get_element_config($element['children']);
                }
            }
        }
        public function load()
        {

            /* Editor only assets */
            add_action('enqueue_block_editor_assets', array($this, 'starcat_review_block'));

            /* For both frontend and editor */
            add_action('enqueue_block_assets', array($this, 'starcat_review_block_assets'));

            register_block_type('starcat-review/starcat-review', array(
                'attributes' => $this->fields,
                'render_callback' => array($this, 'render'),
            ));
        }

        public function starcat_review_block_assets()
        {
            // error_log('starcat_review_block_assets');
            wp_enqueue_style(
                'starcat-review/starcat-review',
                HELPIE_SCR_URL . 'assets/main.bundle.css',
                array('wp-edit-blocks')
            );
        }

        public function starcat_review_block()
        {
            wp_enqueue_script(
                'starcat-review/starcat-review', // Unique handle.
                HELPIE_SCR_URL . 'assets/block.bundle.js', // block js
                array('wp-blocks', 'wp-components', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
                filemtime(HELPIE_SCR_PATH . 'assets/block.bundle.js') // filemtime — Gets file modification time.
            );
            wp_localize_script('starcat-review/starcat-review', 'BlockFields', $this->fields);
        }

        public function render($attributes)
        {
            if (!isset($attributes['id'])) {
                $attributes['id'] = uniqid('starcat-review-');
            }


            $scr = new \StarcatReview\Features\Faq\Faq();

            $style = '';
            if (isset($attributes['style'])) {
                $style = $this->get_block_styles($attributes);
            }

            // error_log($style);
            $view_html =  $scr->get_view($attributes);

            return $style . $view_html;
        }

        public function get_block_styles($attributes)
        {
            $id = $attributes['id'];
            $block_style  = $attributes['style'];
            $style  = '<style>';
            $style .= $this->get_style($block_style, $this->style_config, $id);
            $style .= '</style>';
            return $style;
        }

        public function get_style($block_style, $style_config, $id)
        {
            $style = '';

            foreach ($style_config as $key => $config) {
                $element_name =  $config['name'];

                if (isset($block_style[$element_name])) {
                    $style .= "#" . $id . $config['selector'] . " { ";
                    /* Loop through each style attribute */
                    foreach ($block_style[$element_name] as $key1 => $value) {
                        $style .= $key1 . " : " . $value . ";";
                    }
                    $style .= " } ";
                }

                if (isset($config['children'])) {
                    $style .= $this->get_style($block_style, $config['children'], $id);
                }
            }
            return $style;
        }
    } // END CLASS
}
