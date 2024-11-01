<?php

namespace StarcatReview\App\Components\Form;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Form\Model')) {
    class Model
    {
        public function get_viewProps(array $args, array $user_args = array())
        {
            $collection = $this->get_collectionProps($args);
            $collection = $this->set_user_args_with_collection($collection, $user_args);
            $this->collection = $collection;
            $this->items = $this->get_itemsProps($args);

            $view_props = [
                'collection' => $this->collection,
                'items' => $this->items,
            ];

            // error_log('!!! Form !!!');

            return $view_props;
        }

        protected function get_collectionProps($args)
        {
            // error_log('args : ' . print_r($args, true));

            $collection = [
                'post_id' => $args['post_id'],
                'show_form_title' => $args['show_form_title'],
                'form_title' => $args['form_title'],
                'show_title' => $args['show_title'],
                'show_stats' => $args['show_stats'],
                'show_prosandcons' => $args['enable_pros_cons'],
                'pros-list' => $args['pros-list'],
                'cons-list' => $args['cons-list'],
                'enable_photo_reviews' => $args['enable_photo_reviews'],
                'show_description' => $args['show_description'],
                'show_captcha' => $args['show_captcha'],
                'stats_args' => $args['stats_args'],
                'capability' => $args['capability'],

                // In default show review form
                'show_review_form' => 1,
            ];

            // $collection = array_merge($collection)
            $collection = $this->get_icons($collection);

            return $collection;
        }

        public function get_itemsProps($args)
        {
            $items = [
                'stats' => $this->get_filtered_stats($args),
            ];

            return $items;
        }

        protected function get_filtered_stats($args)
        {
            $stats = [];
            $stat_count = 0;

            if (isset($args['global_stats']) && !empty($args['global_stats'])) {
                foreach ($args['global_stats'] as $allowed_stat) {

                    if ($this->collection['singularity'] == 'single' && $stat_count >= 1) {
                        break;
                    }

                    $stat = strtolower($allowed_stat['stat_name']);

                    $stats[$stat] = ['stat_name' => $stat];

                    $stat_count++;
                }
            }

            return $stats;
        }

        protected function get_icons($collection)
        {
            $image = SCR_URL . 'includes/assets/img/tomato.png';
            $image_outline = SCR_URL . 'includes/assets/img/tomato-outline.png';
            $collection['stats_args']['icon'] = (isset($collection['stats_args']['images']['image']['thumbnail'])) ? $collection['stats_args']['images']['image']['thumbnail'] : $image;
            $collection['stats_args']['outline_icon'] = (isset($collection['stats_args']['images']['image-outline']['thumbnail'])) ? $collection['stats_args']['images']['image-outline']['thumbnail'] : $image_outline;

            if ($collection['stats_args']['source_type'] == 'icon') {
                $collection['stats_args']['icon'] = $collection['stats_args']['icons'] . ' icon';
                $collection['stats_args']['outline_icon'] = $collection['stats_args']['icons'] . ' outline icon';
            }

            return $collection;
        }

        public function set_user_args_with_collection(array $collection, array $user_args = array())
        {
            /**
             * In default behaviour always show the review form, if post_type is enabled in reviews.
             */
            if (empty($user_args)) {
                return $collection;
            }
            /**
             * In users using review form shortcodes, show the form if receive the $use_of_form value as 'add_review' else don't show.
             */
            $use_of_form = isset($user_args['use_of_form']) && $user_args['use_of_form'] == 'edit_review' ? 0 : 1;
            $show_review_form = ($use_of_form && $user_args['show_review_form'] == 1) ? 1 : 0;
            $collection['show_review_form'] = $show_review_form;
            return $collection;
        }
    } // END CLASS
}