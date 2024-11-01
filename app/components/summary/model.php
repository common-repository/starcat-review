<?php

namespace StarcatReview\App\Components\Summary;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Summary\Model')) {
    class Model
    {
        public function get_viewProps(array $args, array $user_args = array())
        {
            // $props = $args;
            $collection = $this->get_collectionProps($args);
            $collection = $this->set_user_args_with_collection($collection, $user_args);
            $viewProps = [
                'collection' => $collection,
                'items' => $this->get_items_props($args),
            ];

            // $props['items']['author'] = ($args['enable-author-review']) ? $args['items'] : [];

            // $props['items']['user'] = $this->get_userItems($args);

            return $viewProps;
        }

        public function get_collectionProps(array $args)
        {

            $collection = [
                'users_title' => sprintf(__('Users Rating (%d)', SCR_DOMAIN), $args['review_count']),
                'author_title' => __('Author Rating', SCR_DOMAIN),
                'no_of_column' => $this->get_no_of_column($args),
                'reviews_title' => $this->get_product_reviews_title(),
                'show' => isset($args['show']) ? $args['show'] : 'both', // user-review, author-review,none
                'is_enable_author' => $args['enable-author-review'],
                'is_enable_prosandcons' => $args['enable_pros_cons'],
                'is_enable_user_review' => $args['enable_user_reviews'],
                // TODO: In default, the "enable_atthachments" option value is true until that option value updating dynamically.
                'is_enable_attachments' => isset($args['enable_atthachments']) ? $args['enable_atthachments'] : true,
            ];

            return $collection;
        }

        public function get_items_props(array $args)
        {
            $stat_args = $args;
            unset($stat_args['items']);

            $author_stat_args = $stat_args;
            $comment_stat_args = $stat_args;

            $author_stat_args['items'] = $args['items']['summary_author'];
            $comment_stat_args['items'] = $args['items']['summary_users'];

            $itemsProps = [
                'author_stat' => $author_stat_args,
                'comment_stat' => $comment_stat_args,
            ];

            if (isset($args['items']['pros-list']) && !empty($args['items']['pros-list'])) {
                $itemsProps['pros-list'] = $args['items']['pros-list'];
            }
            if (isset($args['items']['cons-list']) && !empty($args['items']['cons-list'])) {
                $itemsProps['cons-list'] = $args['items']['cons-list'];
            }

            if (isset($args['items']['attachments']) && !empty($args['items']['attachments'])) {
                $itemsProps['attachments'] = $this->get_all_attachments($args['items']['attachments']);
            }

            return $itemsProps;

        }

        public function get_no_of_column(array $args)
        {
            $no_of_column = 'one';

            $has_author_stat = !empty($args['items']['summary_author']) && $args['enable-author-review'] ? true : false;
            $has_comment_stat = !empty($args['items']['summary_users']) ? true : false;

            if ($has_comment_stat && $has_author_stat) {
                $no_of_column = 'two';
            }

            return $no_of_column;
        }

        protected function get_product_reviews_title()
        {
            $html = '';
            global $product;
            if (isset($product) && $product->get_review_count() && wc_review_ratings_enabled()) {
                $count = $product->get_review_count();
                $reviews_title = sprintf(esc_html(_n('%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce')), esc_html($count), '<span>' . get_the_title() . '</span>');
                $html .= apply_filters('woocommerce_reviews_title', $reviews_title, $count, $product); // WPCS: XSS ok.
            }

            return $html;
        }

        protected function get_all_attachments($attachments_list = [])
        {
            $attachments = [];
            if (!empty($attachments_list)) {
                foreach ($attachments_list as $attachments_item) {
                    if (!empty($attachments_item)) {
                        $attachments = array_merge($attachments, $attachments_item);
                    }
                }
            }
            return $attachments;
        }

        public function set_user_args_with_collection(array $collection, array $user_args = array())
        {
            if (empty($user_args)) {
                return $collection;
            }
            $show_author_review = isset($user_args['show_author_reviews_summary']) && $user_args['show_author_reviews_summary'] == 1 ? true : false;
            $show_user_review = isset($user_args['show_user_reviews_summary']) && $user_args['show_user_reviews_summary'] == 1 ? true : false;
            $show_pros_and_cons_summary = isset($user_args['show_pros_and_cons_summary']) && $user_args['show_pros_and_cons_summary'] == 1 ? true : false;

            $collection['is_enable_author'] = $show_author_review;
            $collection['is_enable_user_review'] = $show_user_review;
            $collection['is_enable_prosandcons'] = $show_pros_and_cons_summary;
            return $collection;
        }
    }
}