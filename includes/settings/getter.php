<?php

namespace StarcatReview\Includes\Settings;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Settings\SCR_Getter')) {

    // SCR - Starcat Review Plugin
    class SCR_Getter
    {
        private static $options;
        private static $defaults;

        public static function get($option_name)
        {

            self::$defaults = self::default_settings();

            // Only set one time
            if (!isset(self::$options) || empty(self::$options)) {
                self::$options = get_option(SCR_OPTIONS); // unique id of the framework
            }

            // error_log('self::$options : ' . print_r(self::$options, true));

            if (isset(self::$options[$option_name])) {
                return self::$options[$option_name];
            } else {
                return self::$defaults[$option_name];
            }
        }

        public static function set($option_name, $option_value)
        {
            // Only set one time
            if (!isset(self::$options) || empty(self::$options)) {
                self::$options = get_option(SCR_OPTIONS); // unique id of the framework
            }

            if (isset($option_value) && !empty($option_value)) {
                self::$options[$option_name] = $option_value;
                update_option(SCR_OPTIONS, self::$options);
            }
        }

        public static function get_settings()
        {
            return self::$options;
        }

        public static function default_settings()
        {
            $defaults = array(

                // General Settings Start
                'template_source' => 'theme',
                'enable-author-review' => true,
                'enable_user_reviews' => true,
                'enable-pros-cons' => true,
                // 'review_enable_post-types' => ['post'], // not-used @since 0.7.6
                'author_review_enabled_post_types' => ['post'], // @since 0.7.6 - new option for reviewing author
                'global_stats' => ['stat_name' => 'Feature'],
                'stat-singularity' => 'single',
                'stats-type' => 'star',
                'stats-source-type' => 'icon',
                'stats-icons' => 'star',
                'stats-images' => [
                    'image' => [
                        'url' => SCR_URL . 'includes/assets/img/tomato.png',
                        'thumbnail' => SCR_URL . 'includes/assets/img/tomato.png',
                    ],

                    'image-outline' => [
                        'url' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                        'thumbnail' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                    ],
                ],
                'stats-show-rating-label' => true,
                'stats-steps' => 'precise',
                'stats-bars-limit' => 100,
                'stats-stars-limit' => 5,
                'stats-animate' => false,
                'stats-no-rated-message' => 'Not Rated Yet !!!',

                // Woocommerce Settings

                'enable_reviews_on_woocommerce' => true,
                'woo_ur_who_can_review' => 'logged_in',
                'woo_enable_pros_cons' => true,
                // 'woo_enable_user_reviews'   => true,
                'woo_enable_voting' => true,
                'woo_show_form_title' => true,
                'woo_stat_singularity' => 'single',
                'woo_global_stats' => ['stat_name' => 'Feature'],
                'woo_stats_source_type' => 'icon',
                'woo_stats_show_rating_label' => true,
                'woo_stats_icons' => 'star',
                'woo_stats_images' => [
                    'image' => [
                        'url' => SCR_URL . 'includes/assets/img/tomato.png',
                        'thumbnail' => SCR_URL . 'includes/assets/img/tomato.png',
                    ],

                    'image-outline' => [
                        'url' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                        'thumbnail' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                    ],
                ],
                'woo_stats_steps' => 'half',
                'woo_show_captcha' => true,

                // Mainpage Settings Start
                'mp_slug' => 'reviews',
                'mp_meta_title' => 'Reviews',
                'mp_meta_description' => 'These are your reviews',
                'mp_template_layout' => 'full-width',
                'mp_components_order' => [
                    'mp_category_listing' => true,
                    'mp_review_listing' => true,
                ],
                'mp_cl_title' => 'Review Categories',
                'mp_cl_description' => true,
                'mp_cl_cols' => '2',

                'mp_rl_title' => 'Reviews Posts',
                'mp_rl_sortby' => 'recent',
                'mp_rl_cols' => '3',

                // Category Page Start
                'cp_template_layout' => 'left-sidebar',
                'cp_controls' => true,
                'cp_search' => true,
                'cp_sortBy' => true,
                // 'cp_num_of_reviews_filter' => true,
                'cp_posts_per_page' => '9',
                'cp_default_sortBy' => 'recent',
                'cp_num_of_cols' => '3',

                // Single Page Start
                // 'sp_show_controls' => true,
                // 'sp_rating_combination' => 'combined',
                // 'sp_stats_order' => [
                //     'enabled' => [],
                //     'disabled' => []
                // ],
                'sp_template_layout' => 'left-sidebar',

                // User Review Start
                // 'ur_enable_post-types' => ['post'],
                'user_review_enabled_post_types' => ['post'],
                'ur_show_controls' => true,
                'ur_controls_subheading' => true,
                'ur_show_search' => true,
                'ur_show_sortBy' => true,
                'ur_enable_replies' => true,
                'ur_who_can_review' => 'logged_in',
                'ur_auto_approve' => false,
                'ur_allow_same_user_can_leave_multiple_reviews' => false,
                'ur_show_list_title' => true,
                'ur_list_title' => 'User Reviews',
                'ur_enable_voting' => true,
                'ur_show_form_title' => true,
                'ur_form_title' => 'Leave a Review',
                'ur_show_title' => true,
                'ur_show_stats' => true,
                'ur_show_description' => true,
                'ur_show_captcha' => true,
                'ur_form_custom_fields' => [],

                // Photos Review start
                'pr_enable' => true,
                'pr_require_photo' => true,
                'pr_photo_order' => 'oldest',
                'pr_photo_size' => 2000,
                'pr_photo_quantity' => 5,

                // Notification
                'ns_from_address' => get_option('admin_email'),
                'ns_subject' => 'Thanks for Puchasing from {{Sitename}}',
                'ns_content' => 'Thank you for purchasing from Starcat Dev. If you liked your product, please leave a review: {{product_review_link}}',
                'ns_disclaimer' => '',
                'ns_time_schedule' => [
                    ['value' => '12', 'unit' => 'hours'],
                    ['value' => '1', 'unit' => 'days'],
                    ['value' => '3', 'unit' => 'days'],
                ]

                // Comparison Table Start
                // 'ct_page' => ''

            );

            return $defaults;
        }

        public static function get_stat_default_args()
        {
            $type = SCR_Getter::get('stats-type');
            $limit = ($type == 'star') ? SCR_Getter::get('stats-stars-limit') : SCR_Getter::get('stats-bars-limit');

            $args = [
                'global_stats' => SCR_Getter::get('global_stats'),
                'singularity' => SCR_Getter::get('stat-singularity'),
                'type' => $type,
                'source_type' => SCR_Getter::get('stats-source-type'),
                'show_rating_label' => SCR_Getter::get('stats-show-rating-label'),
                'icons' => SCR_Getter::get('stats-icons'),
                'images' => SCR_Getter::get('stats-images'),
                'steps' => SCR_Getter::get('stats-steps'),
                'limit' => $limit,
                'animate' => SCR_Getter::get('stats-animate'),
                'no_rated_message' => SCR_Getter::get('stats-no-rated-message'),
                // 'enable_user_reviews' => SCR_Getter::get('enable_user_reviews'),
                'enable_user_reviews' => true, // set default value as true
            ];

            /** Get the woocommerce default settings, if current post_type has a product.  */
            $args = self::get_woo_stat_default_args($args);

            return $args;
        }

        public static function get_woo_stat_default_args($args)
        {
            global $product;
            if (empty($product) && !is_singular('product')) {
                return $args;
            }
            $args['global_stats'] = SCR_Getter::get('woo_global_stats');
            $args['singularity'] = SCR_Getter::get('woo_stat_singularity');
            $args['source_type'] = SCR_Getter::get('woo_stats_source_type');
            $args['show_rating_label'] = SCR_Getter::get('woo_stats_show_rating_label');
            $args['icons'] = SCR_Getter::get('woo_stats_icons');
            $args['images'] = SCR_Getter::get('woo_stats_images');
            $args['steps'] = SCR_Getter::get('woo_stats_steps');
            $args['enable_user_reviews'] = true;
            return $args;
        }

        public static function addons_available_condition()
        {
            $conditions = [];
            $addon_plugins = self::get_addon_plugins_file_constants();

            foreach ($addon_plugins as $addon_short_name => $addon_file_cons) {
                $freemius = 'scr_' . $addon_short_name . '_fs';
                $is_addon_file_defined = defined($addon_file_cons) ? true : false;

                // Assuming addon is not available
                $conditions[$addon_short_name] = false;

                // Addon Plugin and freeemius licences are activated
                if ($is_addon_file_defined && $freemius()->can_use_premium_code()) {
                    $conditions[$addon_short_name] = true;
                }

                // woo notification addon v0.1 compatible support
                if ($addon_short_name == 'wn' && $is_addon_file_defined && get_plugin_data(constant($addon_file_cons))['Version'] == 0.1) {
                    $conditions[$addon_short_name] = function_exists('src_wn') && src_wn()->can_use_premium_code() ? true : false;
                }
            }

            return $conditions;
        }

        public static function get_addon_plugins_file_constants()
        {
            return [
                'ct' => "SCR_CT__FILE__",
                'pr' => "SCR_PR__FILE__",
                'wn' => "SCR_WOO_NOTIFY__FILE__",
                'cpt' => "SCR_CPT__FILE__",
            ];
        }

        public static function get_global_stats()
        {
            /** get default global stats based on general settings */
            $global_stats = SCR_Getter::get('global_stats');

            /** if the current page is product then, retrieve the global stats based on woo-commerce settings */
            if (self::is_admin_product_page() || self::is_single_product_post()) {
                $global_stats = SCR_Getter::get('woo_global_stats');
            }

            return $global_stats;
        }

        public static function get_stat_singularity()
        {
            /** get default stats type based on general settings */
            $singularity = SCR_Getter::get('stat-singularity');

            /** if the current page is product then, get default stat type in woo-commerce settings */
            if (self::is_admin_product_page() || self::is_single_product_post()) {
                $singularity = SCR_Getter::get('woo_stat_singularity');
            }
            return $singularity;
        }

        public static function get_review_enabled_post_types()
        {
            /** TODO: @since - v0.7.6 - use - 'user_review_enabled_post_types' instead of "review_enable_post-types"  */
            // $post_types = self::get('review_enable_post-types');
            $post_types = self::get('user_review_enabled_post_types');
            $enabled_post_types = is_string($post_types) ? [0 => $post_types] : $post_types;
            if (self::is_woocommerce_plugin_active() && self::get('enable_reviews_on_woocommerce')) {
                array_push($enabled_post_types, 'product');
            }

            return $enabled_post_types;
        }

        public static function is_admin_product_page()
        {
            /** Check if the current admin page is a product add (or) edit page in wp  */
            if (is_admin()) {
                $admin_post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
                $post_id = isset($_GET['post']) ? $_GET['post'] : 0;
                $post = get_post($post_id);
                if ($admin_post_type == 'product' || (isset($post) && $post->post_type == 'product')) {
                    return true;
                }
            }
            return false;
        }

        public static function is_woocommerce_plugin_active()
        {
            if (is_plugin_active('woocommerce/woocommerce.php')) {
                return true;
            }
            return false;
        }

        public static function is_single_product_post()
        {
            global $post;
            if (isset($post) && $post->post_type == 'product' && is_singular('product')) {
                return true;
            }
            return false;
        }

        public static function is_enabled_pros_cons()
        {
            $enable_pros_cons = SCR_Getter::get('enable-pros-cons');
            if (self::is_admin_product_page() || self::is_single_product_post()) {
                $enable_pros_cons = SCR_Getter::get('woo_enable_pros_cons');
            }
            return $enable_pros_cons;
        }

        public static function get_recaptcha_site_key()
        {
            $site_key = SCR_Getter::get('recaptcha_site_key');
            if (self::is_single_product_post()) {
                $site_key = SCR_Getter::get('woo_recaptcha_site_key');
            }
            return $site_key;
        }
    } // END CLASS
}