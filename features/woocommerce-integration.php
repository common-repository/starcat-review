<?php

namespace StarcatReview\Features;

use \StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Features\Woocommerce_Integration')) {
    class Woocommerce_Integration
    {
        public function __construct()
        {
            if (SCR_Getter::is_woocommerce_plugin_active()) {
                // Overriding the Existing product and other popular WC addons template by adding 99 as filter priotiry
                add_filter('comments_template', [$this, 'comments_template_loader'], 99);
                add_filter('woocommerce_product_get_rating_html', [$this, 'woocommerce_rating_display'], 10, 3);

                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating');
                add_action('woocommerce_single_product_summary', [$this, 'woocommerce_review_display_overall_rating'], 5);
            }

            add_filter('scr_comment', [$this, 'get_is_review_from_verified_owner']);
            add_action('scr_woocommerce_integration/add_rating_meta', [$this, 'add_rating_meta'], 10, 2);
            add_action('scr_woocommerce_integration/add_verified_owners_meta', [$this, 'add_comment_purchase_verification']);
            add_filter('scr_woocommerce_integration/convert_product_rating_to_stat', [$this, 'convert_product_rating_to_stat']);

        }

        public function comments_template_loader($template)
        {
            $post_type = get_post_type();
            $dir = SCR_PATH . '/app/templates/';
            if ($post_type == 'product') {
                if (file_exists(trailingslashit($dir) . 'reviews-template.php')) {
                    $template = trailingslashit($dir) . 'reviews-template.php';
                }
            }
            return $template;
        }

        public function woocommerce_rating_display()
        {
            global $product;
            $product_id = $product->get_id();
            $overall_ratings = scr_get_overall_rating($product_id);
            return $overall_ratings['dom'];
        }

        public function woocommerce_review_display_overall_rating()
        {
            global $product;
            $product_id = $product->get_id();

            $rating = scr_get_overall_rating($product_id);
            $review_count = scr_get_user_reviews_count($product_id);

            $html = '';

            if (isset($rating['overall']['rating']) && $rating['overall']['rating'] !== 0) {

                $html .= $rating['dom'];
                $html .= '<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(';
                $html .= '<span class="count">' . esc_html($review_count) . '</span>';
                $html .= ' ' . __('customer review', SCR_DOMAIN);
                $html .= ')</a>';
            }

            echo $html;
        }

        public function convert_product_rating_to_stat($comment_id)
        {
            $global_stats = SCR_Getter::get_global_stats();
            $singularity = SCR_Getter::get_stat_singularity();

            $rating = get_comment_meta($comment_id, 'rating', true);
            $is_rating_available = isset($rating) && !empty($rating) ? true : false;

            $comment_stat = [];
            if ($singularity == 'single') {
                $global_stats = [$global_stats[0]];
            }

            // Product 5 Star rating changed to Percentage for better calculation
            if ($is_rating_available) {
                $rating = (!empty($rating)) ? $rating * 20 : $rating;
                foreach ($global_stats as $allowed_stat) {
                    $allowed_stat_name = strtolower($allowed_stat['stat_name']);
                    $comment_stat[$allowed_stat_name] = [
                        'stat_name' => $allowed_stat_name,
                        'rating' => $rating,
                    ];
                }
            }

            return $comment_stat;
        }

        public function add_rating_meta($comment_id, $props)
        {
            $comment = get_comment($comment_id);
            $updated = false;

            if ('product' === get_post_type($comment->comment_post_ID) && isset($props['rating']) && !empty($props['rating'])) {
                update_comment_meta($comment_id, 'rating', round($props['rating'] / 20));
                $updated = true;
            }
            return $updated;
        }

        /* Determine if a review is from a verified owner at submission. */
        public function add_comment_purchase_verification($comment_id)
        {
            $comment = get_comment($comment_id);
            $verified = false;
            if (!$this->is_woocommerce_active()) {
                return $comment;
            }
            if ('product' === get_post_type($comment->comment_post_ID)) {
                $verified = wc_customer_bought_product($comment->comment_author_email, $comment->user_id, $comment->comment_post_ID);
                add_comment_meta($comment_id, 'verified', (int) $verified, true);
            }
            return $verified;
        }

        public function get_is_review_from_verified_owner($comment)
        {
            $comment['is_verified_review'] = false;
            if (!$this->is_woocommerce_active()) {
                return $comment;
            }

            if ($comment['parent'] == 0 && 'product' === get_post_type($comment['post_ID']) && get_option('woocommerce_review_rating_verification_label') === 'yes') {
                $is_customer_bought_product = wc_customer_bought_product($comment['email'], $comment['user_id'], $comment['post_ID']);
                $comment['is_verified_review'] = ($is_customer_bought_product) ? true : false;
            }

            return $comment;
        }

        protected function is_woocommerce_active()
        {
            return is_plugin_active('woocommerce/woocommerce.php');
        }

    }
}