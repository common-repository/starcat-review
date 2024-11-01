<?php

namespace StarcatReview\App\Abstracts;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Abstracts\Widget_Model')) {
    abstract class Widget_Model
    {
        public function __construct($fields_model)
        { }

        public function get_viewProps($args)
        {
            // error_log('get_viewProps $args : ' . print_r($args, true));
            // $args = $this->append_fallbacks($args);

            $default_args = $this->fields_model->get_default_args();
            $args = array_merge($default_args, $args);
            $args = $this->boolean_conversion($args);
            // $this->execute_methods_with_queries($args);

            $viewProps = array(
                'collection' => $this->get_collection_props($args),
                'items' => $this->get_items_props($args),
            );

            // error_log('$viewProps : ' . print_r($viewProps, true));

            return $viewProps;
        }

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


        protected function append_fallbacks($args)
        {

            // Get Default Values from GET - FIELDS
            $args = $this->fields_model->get_default_args();

            return $args;
        }

        public function get_fields()
        {
            return $this->fields_model->get_fields();
        }

        public function get_style_config()
        {
            return $this->style_config->get_config();
        }
    } // END CLASS

}