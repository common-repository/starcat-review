<?php

namespace StarcatReview\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Shortcodes')) {
    class Shortcodes
    {

        public function __construct()
        {
            add_shortcode('starcat_review_list', array($this, 'reviews_list'));
            add_shortcode('starcat_review_comparison_table', array($this, 'comparison_table'));

            /** shortcodes for individual component */
            add_shortcode('starcat_review_summary', array($this, 'review_summary'));
            add_shortcode('starcat_review_user_review_form', array($this, 'user_review_form'));
            add_shortcode('starcat_review_user_review_list', array($this, 'user_review_list'));

            /** shortcodes for overall component */
            add_shortcode('starcat_review_overall_user_review', array($this, 'user_review'));
            $this->shortcode_defaults = new \StarcatReview\App\Services\Shortcodes\Default_Settings();

        }

        public function comparison_table($atts)
        {
            $ct_widget = new \StarcatReviewCt\Widgets\Comparison\Widget();
            $dafaults_args = $ct_widget->get_default_args();
            $widget_args = shortcode_atts($dafaults_args, $atts);
            return $ct_widget->get_view($widget_args);
        }
        public function reviews_list($atts)
        {
            if (!class_exists('\StarcatReviewCpt\Widgets\Review_Listing\Controller')) {
                return;
            }
            $review_listing_widget = new \StarcatReviewCpt\Widgets\Review_Listing\Controller();
            $defaults = $review_listing_widget->get_default_args();
            $widget_args = shortcode_atts($defaults, $atts);
            return $review_listing_widget->get_view($widget_args);
        }

        public function user_review_form($atts)
        {
            $user_args = array(
                'post_id' => get_the_ID(),
                'show_review_form' => 1,
                'use_of_form' => 'add_review',
            );
            $user_args = shortcode_atts($user_args, $atts);
            $user_review_handler = new \StarcatReview\App\Widget_Makers\User_Review\Handler();
            $form = new \StarcatReview\App\Widget_Makers\User_Review\Form();
            $args = $user_review_handler->get_default_args($user_args);
            return $form->get_form($args, $user_args);
        }

        public function user_review_list($atts)
        {
            $default_user_args = $this->shortcode_defaults->get_user_review_list_args();
            $default_user_args['post_id'] = get_the_ID();
            $user_args = shortcode_atts($default_user_args, $atts);
            $user_review_handler = new \StarcatReview\App\Widget_Makers\User_Review\Handler();
            $lists = new \StarcatReview\App\Widget_Makers\User_Review\Lists();
            $form = new \StarcatReview\App\Widget_Makers\User_Review\Form();
            $args = $user_review_handler->get_default_args($user_args);
            $list_view = $lists->get_lists_view($args, $user_args);
            $form_view = $form->get_form($args, $user_args);
            return $form_view . $list_view;
        }

        public function review_summary($atts)
        {
            $default_user_args = $this->shortcode_defaults->get_review_summary_args();
            $default_user_args['post_id'] = get_the_ID();
            $user_args = shortcode_atts($default_user_args, $atts);
            $user_review_handler = new \StarcatReview\App\Widget_Makers\User_Review\Handler();
            $summary = new \StarcatReview\App\Widget_Makers\User_Review\Summary();
            $args = $user_review_handler->get_default_args($user_args);
            $args = $summary->get_settings_args($args);
            $summary_view = $summary->get_summary_view($args, $user_args);
            return $summary_view;
        }

        public function user_review($atts)
        {
            // get default user review overall args
            $default_user_args = $this->shortcode_defaults->get_user_review_args();
            $default_user_args['post_id'] = get_the_ID();
            // merge user default attributes with shortcode atts
            $user_args = shortcode_atts($default_user_args, $atts);
            $user_review_handler = new \StarcatReview\App\Widget_Makers\User_Review\Handler();
            $summary = new \StarcatReview\App\Widget_Makers\User_Review\Summary();
            $form = new \StarcatReview\App\Widget_Makers\User_Review\Form();
            $lists = new \StarcatReview\App\Widget_Makers\User_Review\Lists();

            // set empty value for all view component
            $summary_view = $form_view = $review_list_view = '';

            // get default args
            $args = $user_review_handler->get_default_args($user_args);

            // shown summary, if the user args have show_summary attributes is true. Otherwise doesn't shown summary.
            if ($user_args['show_summary'] == 1) {
                $summary_args = $summary->get_settings_args($args);
                $summary_view = $summary->get_summary_view($summary_args, $user_args);
            }

            // shown review lists, if the user args have show_lists attributes is true. Otherwise doesn't shown the review lists.
            if ($user_args['show_lists'] == 1) {
                $review_list_view .= $form->get_form($args, $user_args);
                $review_list_view .= $lists->get_lists_view($args, $user_args);
            }

            // shown review form, if the user args have show_form attributes is true. Otherwise doesn't shown the review form.
            if ($user_args['show_form'] == 1) {
                $user_args['use_of_form'] = 'add_review';
                $form_view = $form->get_form($args, $user_args);
            }

            return $summary_view . $form_view . $review_list_view;
        }
    } // END CLASS

}