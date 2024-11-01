<?php

namespace StarcatReview\App\Services\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Services\Shortcodes\Builder')) {
    class Builder
    {
        public function init()
        {
            $fields = new \StarcatReview\App\Services\Shortcodes\Builder_Fields();
            $post_field = $fields->get_post_field();

            if (class_exists('\CSF')) {

                $prefix = 'starcat-review-shortcode';

                // Init shortcode builder
                \CSF::createShortcoder($prefix, array(
                    'button_title' => 'Add Starcat Reviews Shortcodes',
                    'select_title' => 'Select a shortcode',
                    'insert_title' => 'Insert Shortcode',
                    'show_in_editor' => true,
                    'gutenberg' => [
                        'title' => 'Starcat Reviews Shortcodes',
                        'icon' => 'screenoptions',
                        'category' => 'widgets',
                        'keywords' => array('starcat', 'shortcode'),
                    ],
                ));

                $show_list_dependencies = array(
                    [
                        'field_id' => 'show_lists',
                        'operand' => '==',
                        'value' => true,
                    ],
                );

                $show_summary_dependencies = array(
                    [
                        'field_id' => 'show_summary',
                        'operand' => '==',
                        'value' => true,
                    ],
                );

                // create builder section
                \CSF::createSection($prefix, array(
                    'title' => 'Starcat Review Overall User Review',
                    'view' => 'normal',
                    'shortcode' => 'starcat_review_overall_user_review',
                    'fields' => [
                        // $fields->get_show_stats_field(),
                        $post_field,
                        $fields->get_show_summary_field(),
                        $fields->get_show_form_field(),
                        $fields->get_show_lists_field(),

                        /** Subfields of to show review summary */
                        $fields->get_heading_content_field('Review Summary Attributes', $show_summary_dependencies),
                        $fields->get_show_author_reviews_summary_field($show_summary_dependencies),
                        $fields->get_show_user_reviews_summary_field($show_summary_dependencies),
                        $fields->get_show_pros_and_cons_field($show_summary_dependencies),

                        /** Subfields of to show review list */
                        $fields->get_heading_content_field('Review Lists Attributes', $show_list_dependencies),
                        $fields->get_show_review_title_field($show_list_dependencies),
                        $fields->get_show_review_search_field($show_list_dependencies),
                        $fields->get_show_review_sort_field($show_list_dependencies),

                    ],
                ));

                \CSF::createSection($prefix, array(
                    'title' => 'Starcat Review Form',
                    'view' => 'normal',
                    'shortcode' => 'starcat_review_user_review_form',
                    'fields' => [
                        $post_field,
                    ],
                ));

                \CSF::createSection($prefix, array(
                    'title' => 'Starcat Review Lists',
                    'view' => 'normal',
                    'shortcode' => 'starcat_review_user_review_list',
                    'fields' => [
                        $post_field,
                        $fields->get_show_review_title_field(),
                        $fields->get_show_review_search_field(),
                        $fields->get_show_review_sort_field(),
                    ],
                ));

                \CSF::createSection($prefix, array(
                    'title' => 'Starcat Review Summary',
                    'view' => 'normal',
                    'shortcode' => 'starcat_review_summary',
                    'fields' => [
                        $post_field,
                        $fields->get_show_author_reviews_summary_field(),
                        $fields->get_show_user_reviews_summary_field(),
                        $fields->get_show_pros_and_cons_field(),
                    ],
                ));
            }

        }
    }
}
