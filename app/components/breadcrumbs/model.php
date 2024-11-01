<?php

namespace StarcatReview\App\Components\Breadcrumbs;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Breadcrumbs\Model')) {
    class Model
    {
        public function __construct()
        {
            $this->extras = new \StarcatReview\Includes\Settings\Extras();
        }

        public function get_scr_info($post_id, $page)
        {
            $breadcrumbs_info = array();

            $mp_scr_section = $this->main_page_section();

            $breadcrumbs_info['post_type'] = array(
                'permalink' => $this->extras->get_mainpage_permalink(),
                'title' => $mp_scr_section['scr_main_title']
            );

            $taxonomy = SCR_CATEGORY;
            if ($page == 'archive') {
                $queried_object = get_queried_object();
                if (isset($queried_object->term_id)) {
                    $term_id = $queried_object->term_id;
                    $term = get_term($term_id);
                    $breadcrumbs_info['term'] = $this->get_term_info($term);
                    $breadcrumbs_info['parent_term'] = $this->get_parent_of_term($term);
                } else {
                    $breadcrumbs_info['term'] = '';
                    $breadcrumbs_info['parent_term'] = '';
                }
            } else {
                $breadcrumbs_info['parent_term'] = $this->get_parent_term_of_post($post_id, $taxonomy);
                $breadcrumbs_info['term'] = $this->get_term_of_post($post_id, $taxonomy);
                $breadcrumbs_info['post'] = $this->get_post_info($post_id);
            }


            return $breadcrumbs_info;
        }

        private function get_term_of_post($post_id, $taxonomy)
        {
            $term_info = array();
            $terms = wp_get_post_terms($post_id, $taxonomy);
            $primary_term_id = $this->get_primary_term($post_id, $taxonomy);

            // Term from Yoast
            if (isset($primary_term_id) && !empty($primary_term_id)) {
                $primary_term = get_term_by('id', $primary_term_id, $taxonomy);
                return $term_info = $this->get_term_info($primary_term);
            }

            // First Term of n terms
            foreach ($terms as $term) {
                $term_info = $this->get_term_info($term);
                break;
            }

            return $term_info;
        }

        private function get_post_info($post_id)
        {
            $post = get_post($post_id);
            // $kb_article = new \Helpie\Includes\Models\Kb_Article($post);

            // use $kb_article->get_title() to get the title, i change  replace $post->post_title
            // 
            return  array(
                'permalink' => get_post_permalink($post_id),
                'title' => $post->post_title
            );
        }
        private function get_parent_term_of_post($post_id, $taxonomy)
        {
            // echo "<li>" . $post_id . "</li>";
            $parent_term_info = array();
            $terms = wp_get_post_terms($post_id, $taxonomy);
            $primary_term = $this->get_primary_term($post_id, $taxonomy);


            if (isset($primary_term) && !empty($primary_term)) {
                $term = get_term_by('id', $primary_term, $taxonomy);
                $parent_term_info = $this->get_parent_of_term($term);
            }

            foreach ($terms as $term) {
                $parent_term_info = $this->get_parent_of_term($term);
                break;
            }

            return $parent_term_info;
        }

        private function get_primary_term($post_id, $taxonomy)
        {

            $primary_term = get_post_meta($post_id, '_yoast_wpseo_primary_' . $taxonomy, true);

            $terms = $this->get_terms($post_id, $taxonomy);

            if (!in_array($primary_term, wp_list_pluck($terms, 'term_id'))) {
                $primary_term = false;
            }

            $primary_term = (int) $primary_term;
            return ($primary_term) ? ($primary_term) : false;
        }
        private function get_terms($post_id, $taxonomy)
        {
            $terms = get_the_terms($post_id, $taxonomy);

            if (!is_array($terms)) {
                $terms = array();
            }

            return $terms;
        }
        private function get_term_info($term)
        {
            return array(
                'permalink' => get_term_link($term),
                'title' => $term->name,
            );
        }

        private function get_parent_of_term($term)
        {
            $parent_term_info = [];
            if (isset($term) && isset($term->parent) && !empty($term->parent) && $term->parent != 0) {
                $parent_term_id = $term->parent;
                $parent_term = get_term($parent_term_id, 'SCR_CATEGORY');
                $parent_term_info = $this->get_term_info($parent_term);
            }

            return $parent_term_info;
        }

        public function main_page_section()
        {
            $mp_section_order =   array(
                'scr_main_title' =>  'Starcat Review',
                'scr_main_subtitle' => 'We’re here to help.',
                'scr_main_page_search_display' => 1,
            );

            //Need Some Clarifications

            return $mp_section_order;
        }

        public function get_style_config()
        {
            $style_config = array(

                'element' => array(
                    'name' => 'scr_breadcrumbs',
                    'selector' => '.scr-breadcrumbs',
                    'label' => __('Breadcrumbs Container', 'elementor'),
                    'styleProps' => array('text-align', 'background', 'border', 'border_radius', 'padding', 'margin'),
                    'children' => array(
                        'label' => array(
                            'name' => 'scr-breadcrumbs',
                            'selector' => '.scr-breadcrumbs a',
                            'label' => __('Link', 'pauple-helpie'),
                            'styleProps' => array('color', 'typography', 'text-align', 'padding'),
                        ),
                        'icon' => array(
                            'name' => 'helpie_element_icon',
                            'selector' => '.scr-breadcrumbs .scr_separator',
                            'label' => __('Voting Icon', 'pauple-helpie'),
                            'styleProps' => array('color', 'text-align'),
                        ),

                    ),
                ),
            );

            return $style_config;
        }
    }
}