<?php

namespace StarcatReview\Includes\Settings;

use \StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Settings\Premium_Tease')) {

    class Premium_Tease
    {
        public function __construct()
        {
            $prefix = SCR_OPTIONS;
            add_filter("csf_{$prefix}_args", [$this, 'csf_framework_override']);
            add_filter("csf_{$prefix}_sections", [$this, 'add_premium_tease_into_sections']);

            // $prefix = '_scr_category_options';
            // add_filter("csf_{$prefix}_sections", [$this, 'add_premium_tease_into_sections']);
        }

        public function csf_framework_override($args)
        {
            if (isset($args) && !empty($args)) {
                $args['class'] = 'scr-csf';
            }

            return $args;
        }

        public function add_premium_tease_into_sections($sections = [])
        {
            $addons_info = $this->get_addons_info();
            if (!empty($sections)) {

                foreach ($sections as $section_key => $section) {
                    $sections[$section_key]['class'] = 'scr-csf__section';
                    if ($section['id'] == 'notification_settings' && !SCR_Getter::addons_available_condition()['wn']) {
                        $sections[$section_key] = $this->add_premium_tease_into_section($section, $addons_info['wn']);
                    }

                    if (in_array($section['id'], array("single_page_settings", "mainpage_settings", 'category_page_settings'))
                        && !SCR_Getter::addons_available_condition()['cpt']) {
                        $sections[$section_key] = $this->add_premium_tease_into_section($section, $addons_info['cpt']);
                    }

                    if ($section['id'] == 'photo_reviews_settings' && !SCR_Getter::addons_available_condition()['pr']) {
                        $sections[$section_key] = $this->add_premium_tease_into_section($section, $addons_info['pr']);
                    }

                    if ($section['id'] == 'comparison_table_settings' && !SCR_Getter::addons_available_condition()['ct']) {
                        $sections[$section_key] = $this->add_premium_tease_into_section($section, $addons_info['ct']);
                    }
                }
            }
            return $sections;
        }

        protected function add_premium_tease_into_section($section = [], $addon_info)
        {
            $section['class'] = 'scr-csf__section--disable';
            $section['fields'] = $this->add_premium_tease_into_fields($section['fields'], $addon_info);
            return $section;
        }

        public function add_premium_tease_into_fields($fields = [], $addon_info)
        {

            if (!empty($fields)) {
                foreach ($fields as $field_key => $field) {
                    if (isset($field['id']) && !empty($field['id'])) {
                        $fields[$field_key]['subtitle'] = $this->premium_feature_sub_title();
                        $fields[$field_key]['class'] = 'scr-csf__field--disable';
                        $fields[$field_key]['attributes'] = [
                            'readonly' => 'readonly',
                            'disabled' => true,
                        ];

                    }
                }
            }

            $fields = $this->add_premium_tease_callback_field($fields, $addon_info);

            return $fields;
        }

        protected function premium_feature_sub_title()
        {
            return '<span style="color: #5cb85c; font-weight: 600;">* ' . __('Premium Feature', SCR_DOMAIN) . '</span>';
        }

        protected function add_premium_tease_callback_field($fields, $addon_info)
        {
            $field_count = count($fields);
            $premium_callback_field_index = $field_count + 1;
            $addon_info['link'] = scr_fs()->addon_url($addon_info['slug']);

            $fields[$premium_callback_field_index] = [
                'class' => 'scr-csf__premium_callback_field',
                'type' => 'callback',
                'function' => 'scr_csf_premium_callback_function',
                'args' => $addon_info,
            ];

            return $fields;
        }

        protected function get_addons_info()
        {
            return [
                'cpt' => [
                    'name' => __('CPT', SCR_DOMAIN),
                    'slug' => 'starcat-review-cpt',
                ],
                'ct' => [
                    'name' => __('Comparison Table', SCR_DOMAIN),
                    'slug' => 'starcat-review-ct',
                ],
                'pr' => [
                    'name' => __('Photo Reviews', SCR_DOMAIN),
                    'slug' => 'starcat-review-pr',
                ],
                'wn' => [
                    'name' => __('Woocommerce Notification', SCR_DOMAIN),
                    'slug' => 'starcat-review-woo-notify',
                ],
            ];
        }
    }
}
