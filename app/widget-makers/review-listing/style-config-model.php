<?php

namespace StarcatReview\App\Widget_Makers\Review_Listing;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\StarcatReview\App\Widget_Makers\Review_Listing\Style_Config_Model')) {
    class Style_Config_Model
    {
        public function get_config()
        {
            $style_config = array(
                // 'collection' => array(
                //     'name' => SCR_POST_TYPE,
                //     'selector' => '.starcat-review.accordions .accordion',
                //     'label' => __('SCR', 'starcat-review'),
                //     'styleProps' => array( 'background', 'border', 'padding', 'margin')
                // ),

                // 'title_icon' => array(
                //     'name' => 'starcat_review_title_icon',
                //     'selector' => '.starcat-review.accordions .collection-title i',
                //     'label' => __('Title Icon', 'starcat-review'),
                //     'styleProps' => array( 'icon', 'position', 'color')
                // ),

                'title' => array(
                    'name' => 'starcat_review_title',
                    'selector' => '.starcat-review.accordions .collection-title',
                    'label' => __('Title', 'starcat-review'),
                    'styleProps' => array('color', 'typography', 'text-align', 'border', 'background', 'padding', 'margin')
                ),

                'element' => array(
                    'name' => 'helpie_element',
                    'selector' => '.starcat-review.accordions .accordion__item',
                    'label' => __('Single Item', 'starcat-review'),
                    // 'styleProps' => array( 'background', 'border', 'padding', 'margin'),
                    'children' => array(
                        'header' => array(
                            'name' => 'helpie_element_header',
                            'selector' => '.starcat-review.accordions .accordion__header',
                            'label' => __('Single Item - Header', 'starcat-review'),
                            'styleProps' => array('background', 'color', 'typography', 'text-align', 'border')
                        ),
                        'content' => array(
                            'name' => 'helpie_element_content',
                            'selector' => '.starcat-review.accordions .accordion__body',
                            'label' => __('Single Item - Body', 'starcat-review'),
                            'styleProps' => array('background', 'color', 'typography', 'text-align'),
                        )

                    )
                )
            );

            return $style_config;
        }
    }
}