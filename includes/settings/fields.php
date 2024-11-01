<?php

namespace StarcatReview\Includes\Settings;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Settings\Fields')) {

    // SCR - Starcat Review Plugin
    class Fields
    {

        public static function single_details_fields()
        {
            return [
                array(
                    'id' => 'pro-1',
                    'type' => 'text',
                    'title' => 'Affiliate Button Text',
                ),
                array(
                    'id' => 'pro-1',
                    'type' => 'text',
                    'title' => 'Affiliate Link',
                ),

                array(
                    'id' => 'opt-select-1',
                    'type' => 'select',
                    'title' => 'Current',
                    'placeholder' => 'Select an option',
                    'options' => array(
                        'option-1' => '$',
                        'option-2' => 'Rs',
                        'option-3' => 'Option 3',
                    ),
                    'default' => 'option-1',
                ),

                array(
                    'id' => 'pro-1',
                    'type' => 'text',
                    'title' => 'Product Price',
                ),
            ];
        }

        // Features - Main Settings Options
        public static function stats_field()
        {
            return
            array(

                array(
                    'id' => 'stats-list',
                    'type' => 'repeater',
                    'title' => 'Stats',
                    'fields' => array(

                        array(
                            'id' => 'stat_name',
                            'type' => 'text',
                            'title' => 'Stat Name',
                        ),
                        array(
                            'id' => 'stat_type',
                            'type' => 'select',
                            'title' => 'Select',
                            'placeholder' => 'Select an option',
                            'options' => array(
                                'icon' => 'Icon',
                                'image' => 'Image',
                            ),
                            'default' => 'image',
                        ),
                        array(
                            'id' => 'stat_icon',
                            'type' => 'icon',
                            'title' => 'Icon',
                            'dependency' => array('stat_type', '==', 'icon'),
                        ),
                        array(
                            'id' => 'stat_image',
                            'type' => 'media',
                            'title' => __('Media', SCR_DOMAIN),
                            'library' => 'image',
                            'dependency' => array('stat_type', '==', 'image'),
                        ),
                    ),
                ),
            );
        }

        public static function rich_snippets_fields()
        {
            return array(
                array(
                    'id' => 'opt-select-1',
                    'type' => 'select',
                    'title' => 'Rich Snippet Type',
                    'placeholder' => 'Select an option',
                    'options' => array(
                        'option-1' => 'Option 1',
                        'option-2' => 'Option 2',
                        'option-3' => 'Option 3',
                    ),
                    'default' => 'option-2',
                ),

            );
        }

        public static function single_post_prosandcons_fields($id = 'pros-list', $title = 'Pros')
        {
            return array(
                array(
                    'id' => $id,
                    'type' => 'repeater',
                    'title' => __($title, SCR_DOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'item',
                            'type' => 'text',
                            'placeholder' => __($title, SCR_DOMAIN),
                        ),
                    ),
                ),
            );
        }
    } // END CLASS
}
