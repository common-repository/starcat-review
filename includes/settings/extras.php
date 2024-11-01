<?php

namespace StarcatReview\Includes\Settings;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Settings\Extras')) {
    class Extras
    {
        public function __construct()
        {}

        public function get_main_page_url()
        {
            $href = $this->get_mainpage_permalink();

            $html = "<a target ='_blank' class='ui labeled icon small button' href=" . $href . ">";
            $html .= __("Visit Main Page", SCR_DOMAIN);
            $html .= "<i class='external alternate icon'></i>";
            $html .= "</a>";

            return $html;
        }

        public function get_mainpage_permalink()
        {

            // if ($this->mp_settings->get_mp_location() == 'archive') {
            return get_post_type_archive_link('starcat_review');
            // } else {
            //     $post_id = $this->get_mp_selected_page();
            //     return get_permalink($post_id);
            // }
        }
    } // END CLASS
}
