<?php

namespace StarcatReview\Includes;

use \StarcatReview\Includes\Settings\Fields as Post_MetBox_Fields;
use \StarcatReview\Includes\Settings\SCR_Getter;
use \StarcatReview\Includes\Translations;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Settings')) {
    class Settings
    {
        public $fields;
        public function __construct()
        {
            $this->init_settings();
            new \StarcatReview\Includes\Settings\Premium_Tease();
        }

        public function init_settings()
        {
            include_once SCR_PATH . 'includes/settings/helper.php';

            if (class_exists('\CSF')) {

                // Set a unique slug-like ID
                $prefix = SCR_OPTIONS; // scr_options

                $options = array(
                    'menu_title' => __('Starcat Settings', 'starcat-review'),
                    'menu_parent' => 'edit.php?post_type=starcat_review',
                    'menu_type' => 'menu', // menu, submenu, options, theme, etc.
                    'menu_slug' => 'scr-settings',
                    'framework_title' => __('Starcat Settings', 'starcat-review'),
                    'theme' => 'light',
                    'show_search' => false, // TODO: Enable once autofill password is fixed
                );

                // Create options
                \CSF::createOptions($prefix, $options);

                $this->settings_tabs($prefix);
                $this->single_post_meta_fields();
                if (SCR_Getter::addons_available_condition()['cpt']) {
                    $this->category_meta_fields();
                }
            }
        }

        public function settings_tabs($prefix)
        {
            $this->woocommerce_settings($prefix);
            $this->general_settings($prefix);
            $this->user_review_settings($prefix);
            $this->mainpage_settings($prefix);
            $this->category_page_settings($prefix);
            $this->single_page_settings($prefix);
            $this->photo_reviews_settings($prefix);
            $this->notification_settings($prefix);
            $this->ct_settings($prefix);
        }

        public function ct_settings($prefix)
        {
            // error_log('CT Settings');
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'comparison_table_settings',
                    'title' => Translations::getStrings('ComparisonTable'),
                    'icon' => 'fa fa-table',
                    'class' => 'some',
                    'fields' => array(
                        array(
                            'type' => 'submessage',
                            'style' => 'success',
                            'content' => __('You can use Comparison Tables via [starcat_review_comparison_table] shortcode with args post_type and posts(post_id)', SCR_DOMAIN),
                        ),
                        array(
                            'type' => 'submessage',
                            'style' => 'success',
                            'content' => __("Example: [starcat_review_comparison_table post_type='product' posts='213,245,256']", SCR_DOMAIN),
                        ),
                    ),
                )
            );
        }

        public function notification_settings($prefix)
        {
            $admin_email = get_option('admin_email');
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'notification_settings',
                    'title' => Translations::getStrings('WoocommerceNotification'),
                    'icon' => 'fa fa-bell',
                    'fields' => array(
                        array(
                            'type' => 'heading',
                            'content' => __('Woocommerce Notification Settings to notify users after they complete their orders', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'ns_from_address', // ns: notification_settings
                            'type' => 'text',
                            'title' => __('Enter From Address', SCR_DOMAIN),
                            'placeholder' => $admin_email,
                            'default' => $admin_email,
                            'desc' => __('Make sure this is a valid email address. Invalid emails maybe marked as spam', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'ns_subject', // ns: notification_settings
                            'type' => 'text',
                            'title' => __('Subject', SCR_DOMAIN),
                            'placeholder' => __('Subject of the Email', SCR_DOMAIN),
                            'default' => 'Thank you for Purchasing from {{Sitename}}',
                            'desc' => __('Use {{sitename}} to use the name of your website dynamically. This improves your anti-spam score', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'ns_content', // ns: notification_settings
                            'type' => 'textarea',
                            'title' => __('Body of the Email', SCR_DOMAIN),
                            'placeholder' => __('Body of the email', SCR_DOMAIN),
                            'default' => 'Thank you for purchasing from Starcat Dev. If you liked your product, please leave a review: {{product_review_link}}',
                            'desc' => __('Use {{product_review_link}} to add links to purchased product pages', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'ns_disclaimer', // ns: notification_settings
                            'type' => 'textarea',
                            'title' => __('Disclaimer', SCR_DOMAIN),
                            'placeholder' => __('Disclaimer', SCR_DOMAIN),
                            'desc' => '<strong>' . __('Shown at the footer of the email', SCR_DOMAIN) . '</strong>',
                        ),
                        array(
                            'id' => 'ns_time_schedule', // ns: notification_settings
                            'type' => 'repeater',
                            'title' => __('Notification Time Schedule', SCR_DOMAIN),
                            'desc' => sprintf(__('%s Hours/Days from the time of order completion. You can create multiple remainders (example: 24 hours, 3 days, 7 days from purchase)', SCR_DOMAIN), '<strong>' . __('Notification Schedule', SCR_DOMAIN) . '</strong> :'),
                            'fields' => array(

                                array(
                                    'id' => 'value',
                                    'type' => 'text',
                                    'title' => __('Time Value', SCR_DOMAIN),
                                ),
                                array(
                                    'id' => 'unit',
                                    'type' => 'select',
                                    'title' => __('Time Unit', SCR_DOMAIN),
                                    'chosen' => true,
                                    'placeholder' => __('Select an option', SCR_DOMAIN),
                                    'options' => array(
                                        'hours' => __('Hours', SCR_DOMAIN),
                                        'days' => __('Days', SCR_DOMAIN),
                                    ),
                                    'default' => 'days',
                                ),

                            ),
                            'default' => array(
                                array(
                                    'value' => '24',
                                    'unit' => 'hours',
                                ),
                                array(
                                    'value' => '1',
                                    'unit' => 'days',
                                ),
                                array(
                                    'value' => '3',
                                    'unit' => 'days',
                                ),
                            ),
                        ),

                    ),
                )
            );
        }
        public function category_meta_fields()
        {
            // taxonomy-prefix
            $prefix = '_scr_category_options';
            // Create taxonomy options
            \CSF::createTaxonomyOptions($prefix, array(
                'taxonomy' => SCR_CATEGORY,
                'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`
            ));

            // Create a section
            \CSF::createSection($prefix, array(
                'fields' => array(
                    array(
                        'id' => 'featured_image',
                        'type' => 'media',
                        'title' => 'Media',
                        'library' => 'image',
                    ),

                ),
            ));
        }

        // Note : Not used in MVP but it will be after MVP release
        public function comparison_table_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'comparison_table_settings',
                    'title' => Translations::getStrings('ComparisonTable'),
                    'icon' => 'fa fa-table',
                    'fields' => array(

                        array(
                            'id' => 'ct_page', // ct: comparison_table
                            'type' => 'select',
                            'title' => __('Select with pages', SCR_DOMAIN),
                            'placeholder' => __('Select a page', SCR_DOMAIN),
                            'options' => 'pages',
                            'desc' => '<strong>  ' . __('[advanced_review_comparison_table] -> Add this shortcode to the selected page', SCR_DOMAIN) . '.</strong>',
                        ),

                    ),
                )
            );
        }

        public function user_review_settings($prefix)
        {
            $post_types = $this->get_post_types(['product']);
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'user_review_settings',
                    'title' => Translations::getStrings('UserReviews'),
                    'icon' => 'fa fa-comment',
                    'fields' => array(

                        // array(
                        //     'id' => 'ur_enable_post-types',
                        //     'type' => 'select',
                        //     'title' => 'Enable Reviews for custom post types',
                        //     'placeholder' => 'Select a Post Type',
                        //     'multiple' => true,
                        //     'chosen' => true,
                        //     'options' => 'post_types',
                        //     'query_args' => array(
                        //         'post_type' => 'post',
                        //     ),
                        //     'default' => 'post',
                        // ),

                        // array(
                        //     'id' => 'ur_show_controls',
                        //     'type' => 'switcher',
                        //     'title' => __('Show Reviews Controls', SCR_DOMAIN),
                        //     'default' => true,
                        // ),

                        // array(
                        //     'id'      => 'ur_list_controls',
                        //     'type'    => 'fieldset',
                        //     'title'   => 'Reviews Controls',
                        //     'dependency' => array('ur_show_controls', '==', 'true'),
                        //     'fields' => array(

                        //         array(
                        //             'id' => 'ur_show_search',
                        //             'type' => 'switcher',
                        //             'title' => __('Show Search', SCR_DOMAIN),
                        //             'default' => true,
                        //         ),
                        //         array(
                        //             'id' => 'ur_show_sortBy',
                        //             'type' => 'switcher',
                        //             'title' => __('Show SortBy', SCR_DOMAIN),
                        //             'default' => true,
                        //         )
                        //     )
                        // ),

                        // array(
                        //     'id' => 'ur_enable_replies',
                        //     'type' => 'switcher',
                        //     'title' => __('Enable Replies to Reviews', SCR_DOMAIN),
                        //     'default' => true,
                        // ),

                        array(
                            'id' => 'user_review_enabled_post_types',
                            'type' => 'select',
                            'title' => __('Where to include user reviews?', SCR_DOMAIN),
                            'chosen' => true,
                            'placeholder' => 'Select post types',
                            'options' => $post_types,
                            'multiple' => true,
                            'query_args' => array(
                                'post_type' => 'post',
                            ),
                            'default' => ['post'],
                        ),

                        array(
                            'id' => 'ur_who_can_review',
                            'type' => 'select',
                            'title' => __('Who Can Review', SCR_DOMAIN),
                            'desc' => __('Selecting Everyone will let even Non Logged-in Users add reviews', SCR_DOMAIN),
                            'options' => array(
                                'logged_in' => __('Logged In Users', SCR_DOMAIN),
                                'everyone' => __('Everyone', SCR_DOMAIN),
                            ),
                            'default' => 'logged_in',
                        ),
                        // array(
                        //     'id' => 'enable_user_reviews',
                        //     'type' => 'switcher',
                        //     'title' => __('Enable Users Reviews', SCR_DOMAIN),
                        //     'default' => true,
                        // ),
                        array(
                            'id' => 'ur_auto_approve',
                            'type' => 'switcher',
                            'title' => __('Auto Approve Review', SCR_DOMAIN),
                            'desc' => __("Publish the submitted review directly. Don't ask for approval", SCR_DOMAIN),
                            'default' => false,
                        ),

                        array(
                            'id' => 'ur_allow_same_user_can_leave_multiple_reviews',
                            'type' => 'switcher',
                            'title' => __('Allow Same User Can leave More than One Review', SCR_DOMAIN),
                            'desc' => __('Allow Same user to leave more than one review on a single post', SCR_DOMAIN),
                            'default' => false,
                        ),

                        array(
                            'id' => 'ur_show_list_title',
                            'type' => 'switcher',
                            'title' => __('Show User Reviews List Title', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'ur_list_title',
                            'type' => 'text',
                            'title' => __('User Reviews List Title', SCR_DOMAIN),
                            'dependency' => array('ur_show_list_title', '==', 'true'),
                            'default' => 'User Reviews',
                        ),

                        array(
                            'id' => 'ur_enable_voting',
                            'type' => 'switcher',
                            'title' => __('Voting', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'type' => 'subheading',
                            'content' => __('User Review Form', SCR_DOMAIN),
                        ),

                        array(
                            'id' => 'ur_show_form_title',
                            'type' => 'switcher',
                            'title' => __('Show Form Title', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'ur_form_title',
                            'type' => 'text',
                            'title' => __('Form Title', SCR_DOMAIN),
                            'dependency' => array('ur_show_form_title', '==', 'true'),
                            'default' => 'Leave a Review',
                        ),

                        array(
                            'id' => 'ur_show_title',
                            'type' => 'switcher',
                            'title' => __('Show Title', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'ur_show_stats',
                            'type' => 'switcher',
                            'title' => __('Show Stat', SCR_DOMAIN),
                            'default' => true,
                            'desc' => __('User Review Rating options are based on stats option from general settings section', SCR_DOMAIN),
                        ),

                        array(
                            'id' => 'ur_show_description',
                            'type' => 'switcher',
                            'title' => __('Show Description', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'ur_show_captcha',
                            'type' => 'switcher',
                            'title' => __('Show reCAPTCHA (v2 checkbox)', SCR_DOMAIN),
                            'default' => false,
                            'desc' => sprintf(__('Register for reCAPTCHA v2 at %s to get your site key and secret key. Make sure to add your domain name in the settings at the reCAPTCHA website. Read More at %s. %s reCAPTCHA v3 will not work, just v2. v3 will be added soon.', SCR_DOMAIN), '<a href="https://www.google.com/recaptcha">https://www.google.com/recaptcha</a>', '<a href="https://paupledocs.gitbook.io/starcat-documentation/">Starcat Reviews - Docs</a>', '<strong>' . __('Note', SCR_DOMAIN) . '</strong> : '),
                        ),

                        array(
                            'id' => 'recaptcha_site_key',
                            'type' => 'text',
                            'title' => __('reCAPTCHA Site Key', SCR_DOMAIN),
                            'dependency' => array('ur_show_captcha', '==', 'true'),
                            'validate' => 'csf_validate_recaptcha_site_key',
                        ),

                        array(
                            'id' => 'recaptcha_secret_key',
                            'type' => 'text',
                            'title' => __('reCAPTCHA Secret Key', SCR_DOMAIN),
                            'default' => '',
                            'dependency' => array('ur_show_captcha', '==', 'true'),
                            'validate' => 'csf_validate_recaptcha_secret_key',
                        ),

                        // array(
                        //     'id' => 'ur_show_prosandcons',
                        //     'type' => 'switcher',
                        //     'title' => __('Show Pros and Cons', SCR_DOMAIN),
                        //     'default' => true,
                        // ),

                        // array(
                        //     'id'     => 'ur_form_custom_fields',
                        //     'type'   => 'repeater',
                        //     'title'  => 'Custom Form Fields',
                        //     'fields' => array(

                        //         array(
                        //             'id'    => 'field_name',
                        //             'type'  => 'text',
                        //             'placeholder' => 'Field Name',
                        //             'title' => 'Name'
                        //         ),

                        //         array(
                        //             'id'    => 'field_type',
                        //             'type'  => 'select',
                        //             'desc' => 'Field Type',
                        //             'title' => 'Type',
                        //             'options' => array(
                        //                 'text' => 'Text',
                        //                 'textarea' => 'Text Area'
                        //             )
                        //         ),

                        //     ),
                        // ),

                    ),
                )
            );
        }

        public function photo_reviews_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'photo_reviews_settings',
                    'title' => Translations::getStrings('PhotoReviews'),
                    'icon' => 'fa fa-image',
                    'fields' => array(
                        array(
                            'id' => 'pr_enable',
                            'type' => 'switcher',
                            'title' => __('Enable Photo Reviews', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'pr_require_photo',
                            'type' => 'switcher',
                            'title' => __('Photo required', SCR_DOMAIN),
                            'default' => true,
                            'desc' => __('Make this a required field', SCR_DOMAIN),
                            'dependency' => array('pr_enable', '==', 'true'),
                        ),

                        // array(
                        //     'id' => 'pr_photo_order',
                        //     'type' => 'select',
                        //     'chosen' => true,
                        //     'title' => __('Showing Photo Order', SCR_DOMAIN),
                        //     'placeholder' => __('Select an option', SCR_DOMAIN),
                        //     'options' => array(
                        //         'newest' => __('Newest First', SCR_DOMAIN),
                        //         'oldest' => __('Oldest First', SCR_DOMAIN),
                        //     ),
                        //     'default' => 'oldest',
                        //     'dependency' => array('pr_enable', '==', 'true'),
                        // ),

                        array(
                            'id' => 'pr_photo_size',
                            'type' => 'text',
                            'title' => __('Maximum photo size', SCR_DOMAIN),
                            'default' => 2000,
                            'desc' => __('kB (Max 307200kB)', SCR_DOMAIN),
                            'dependency' => array('pr_enable', '==', 'true'),
                        ),
                        array(
                            'id' => 'pr_photo_quantity',
                            'title' => __('Maximum photo quantity', SCR_DOMAIN),
                            'type' => 'slider',
                            'min' => 1,
                            'max' => 20,
                            'step' => 1,
                            'unit' => '#',
                            'default' => 5,
                            'desc' => __('Maximum value: 20', SCR_DOMAIN),
                            'dependency' => array('pr_enable', '==', 'true'),
                        ),
                    ),
                )
            );
        }

        public function single_page_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'single_page_settings',
                    'title' => Translations::getStrings('SinglePage'),
                    'icon' => 'fa fa-file',
                    'fields' => array(
                        array(
                            'type' => 'submessage',
                            // 'style'   => 'info',
                            'content' => __('These settings are only for starcat-review post type.', SCR_DOMAIN),
                        ),
                        array(
                            'type' => 'subheading',
                            'content' => __('Template', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'sp_template_layout',
                            'type' => 'image_select',
                            'title' => __('Template', SCR_DOMAIN),
                            'options' => array(
                                'left-sidebar' => SCR_URL . '/includes/assets/img/templates/left-sidebar.png',
                                'right-sidebar' => SCR_URL . '/includes/assets/img/templates/right-sidebar.png',
                                'full-width' => SCR_URL . '/includes/assets/img/templates/full-width.png',
                            ),
                            'default' => 'full-width',
                            'desc' => __('Layout Types for your Single Review Page. Left / Right Sidebar, No-sidebar.', SCR_DOMAIN),
                        ),
                        // A Submessage
                        array(
                            'type' => 'submessage',
                            'style' => 'success',
                            'content' => __('You can add widgets to your sidebar from Appearance -> Widgets', SCR_DOMAIN),
                        ),

                        array(
                            'id' => 'sp_show_controls',
                            'type' => 'switcher',
                            'title' => __('Show Controls', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'sp_rating_combination',
                            'type' => 'select',
                            'chosen' => true,
                            'title' => __('Show Review', SCR_DOMAIN),
                            'placeholder' => __('Select an option', SCR_DOMAIN),
                            'options' => array(
                                'author' => __('Author Only', SCR_DOMAIN),
                                'user' => __('User Only', SCR_DOMAIN),
                                'both' => __('Author and User both', SCR_DOMAIN),
                                'combined' => __('Combined Rating', SCR_DOMAIN),
                            ),
                            'default' => 'combined',
                        ),
                    ),
                )
            );
        }

        public function category_page_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'category_page_settings',
                    'title' => Translations::getStrings('CategoryPage'),
                    'icon' => 'fa fa-folder-open',
                    'fields' => array(
                        array(
                            'type' => 'subheading',
                            'content' => __('Template', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'cp_template_layout',
                            'type' => 'image_select',
                            'title' => __('Template', SCR_DOMAIN),
                            'options' => array(
                                'left-sidebar' => SCR_URL . '/includes/assets/img/templates/left-sidebar.png',
                                'right-sidebar' => SCR_URL . '/includes/assets/img/templates/right-sidebar.png',
                                'full-width' => SCR_URL . '/includes/assets/img/templates/full-width.png',
                            ),
                            'default' => 'full-width',
                            'desc' => __('Layout Types for your Review Categories Page. Left / Right Sidebar, No-sidebar', SCR_DOMAIN),
                        ),
                        // A Submessage
                        array(
                            'type' => 'submessage',
                            'style' => 'success',
                            'content' => __('You can add widgets to your sidebar from Appearance -> Widgets', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'cp_controls',
                            'type' => 'switcher',
                            'title' => __('Show Controls', SCR_DOMAIN),
                            'default' => true,
                        ),
                        array(
                            'id' => 'cp_controls_subheading',
                            'type' => 'subheading',
                            'content' => __('Controls', SCR_DOMAIN),
                            'dependency' => array('cp_controls', '==', 'true'),
                        ),
                        array(
                            'id' => 'cp_search',
                            'type' => 'switcher',
                            'title' => __('Show Search', SCR_DOMAIN),
                            'default' => true,
                            'dependency' => array('cp_controls', '==', 'true'),
                        ),
                        array(
                            'id' => 'cp_sortBy',
                            'type' => 'switcher',
                            'title' => __('Show SortBy', SCR_DOMAIN),
                            'default' => true,
                            'dependency' => array('cp_controls', '==', 'true'),
                        ),
                        // array(
                        //     'id' => 'cp_num_of_reviews_filter',
                        //     'type' => 'switcher',
                        //     'title' => __('Show Number of Review Filter', SCR_DOMAIN),
                        //     'default' => true,
                        //     'dependency' => array('cp_controls', '==', 'true'),
                        // ),

                        array(
                            'id' => 'cp_listing_options_subheading',
                            'type' => 'subheading',
                            'content' => __('Listing Options', SCR_DOMAIN),

                        ),

                        array(
                            'id' => 'cp_posts_per_page',
                            'type' => 'text',
                            'title' => __('Posts Per Page', SCR_DOMAIN),
                            'default' => '9',
                        ),
                        array(
                            'id' => 'cp_default_sortBy',
                            'type' => 'select',
                            'chosen' => true,
                            'title' => __('Default Sort By', SCR_DOMAIN),
                            'placeholder' => __('Select a sortby option', SCR_DOMAIN),
                            'options' => array(
                                'alphabetical_asc' => __('Alphabetical Ascending', SCR_DOMAIN),
                                'alphabetical_desc' => __('Alphabetical Descending', SCR_DOMAIN),
                                'recent' => __('Recent', SCR_DOMAIN),
                                'updated' => __('Recently Updated', SCR_DOMAIN),
                                // 'num_of_reviews' => 'Number of Reviews',
                            ),
                            'default' => 'recent',
                        ),
                        array(
                            'id' => 'cp_num_of_cols',
                            'type' => 'select',
                            'chosen' => true,
                            'title' => __('Num Of Columns', SCR_DOMAIN),
                            'placeholder' => __('Select an option', SCR_DOMAIN),
                            'options' => array(
                                '1' => 1,
                                '2' => 2,
                                '3' => 3,
                                '4' => 4,
                            ),
                            'default' => '3',
                        ),
                    ),
                )
            );
        }
        public function mainpage_settings($prefix)
        {
            $extras = new \StarcatReview\Includes\Settings\Extras();
            $main_page_button = $extras->get_main_page_url();

            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'mainpage_settings',
                    'title' => Translations::getStrings('MainPage'),
                    'icon' => 'fa fa-home',
                    'fields' => array(

                        array(
                            'id' => 'mp_meta_title',
                            'type' => 'text',
                            'title' => __('Main Page Meta Title', SCR_DOMAIN),
                            'desc' => '<strong>' . __('Note', SCR_DOMAIN) . '</strong> : ' . __('Keep your meta title between 60 and 64 characters.', SCR_DOMAIN),
                            'default' => 'Reviews',
                        ),
                        array(
                            'id' => 'mp_meta_description',
                            'type' => 'text',
                            'title' => __('Main Page Meta Description', SCR_DOMAIN),
                            'desc' => '<strong>' . __('Note', SCR_DOMAIN) . '</strong> : ' . __('Keep your meta descriptions between 150 and 154 characters.', SCR_DOMAIN),
                            'default' => 'These are your reviews',
                        ),
                        array(
                            'id' => 'mp_slug',
                            'type' => 'text',
                            'title' => __('Main Page Slug', SCR_DOMAIN),
                            'default' => 'reviews',
                        ),
                        array(
                            'type' => 'content',
                            'content' => '<div class="button-container">'
                            . '<span><b>' . __('Where is my main page?', SCR_DOMAIN) . '</b></span>'
                            . '<br>'
                            . $main_page_button . '<span>' . __('Save and Refresh Page if you changed it.', SCR_DOMAIN) . '</span></div>',
                        ),
                        array(
                            'type' => 'subheading',
                            'content' => __('Template', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'mp_template_layout',
                            'type' => 'image_select',
                            'title' => __('Template', SCR_DOMAIN),
                            'options' => array(
                                'left-sidebar' => SCR_URL . '/includes/assets/img/templates/left-sidebar.png',
                                'right-sidebar' => SCR_URL . '/includes/assets/img/templates/right-sidebar.png',
                                'full-width' => SCR_URL . '/includes/assets/img/templates/full-width.png',
                            ),
                            'default' => 'full-width',
                            'desc' => __('Layout Types for your Reviews Main Page. Left / Right Sidebar, No-sidebar', SCR_DOMAIN),
                        ),
                        // A Submessage
                        array(
                            'type' => 'submessage',
                            'style' => 'success',
                            'content' => __('You can add widgets to your sidebar from Appearance -> Widgets', SCR_DOMAIN),
                        ),
                        array(
                            'id' => 'mp_components_order',
                            'type' => 'sortable',
                            'title' => __('Components Control', SCR_DOMAIN),
                            'desc' => __('Controls order and visibility of these components in Main Page', SCR_DOMAIN),
                            'fields' => array(
                                array(
                                    'id' => 'mp_category_listing',
                                    'type' => 'switcher',
                                    'title' => __('Category Listing', SCR_DOMAIN),
                                    'default' => true,
                                ),
                                array(
                                    'id' => 'mp_review_listing',
                                    'type' => 'switcher',
                                    'title' => __('Review Listing', SCR_DOMAIN),
                                    'default' => true,
                                ),
                            ),
                            'default' => array(
                                'mp_category_listing' => true,
                                'mp_review_listing' => true,
                            ),
                        ),

                        // cl ( Category Lising ) -- Main Page
                        array(
                            'id' => 'mp_cl_heading',
                            'type' => 'subheading',
                            'content' => __('Category Listing', SCR_DOMAIN),
                            'dependency' => array('mp_category_listing', '==', 'true'),
                        ),

                        array(
                            'id' => 'mp_cl_title',
                            'type' => 'text',
                            'title' => __('Title', SCR_DOMAIN),
                            'default' => 'Review Categories',
                            'dependency' => array('mp_category_listing', '==', 'true'),
                        ),

                        array(
                            'id' => 'mp_cl_description',
                            'type' => 'switcher',
                            'title' => __('Show Description', SCR_DOMAIN),
                            'dependency' => array('mp_category_listing', '==', 'true'),
                            'default' => true,
                        ),

                        array(
                            'id' => 'mp_cl_cols',
                            'type' => 'select',
                            'chosen' => true,
                            'title' => __('Num Of Columns', SCR_DOMAIN),
                            'placeholder' => __('Select an option', SCR_DOMAIN),
                            'options' => array(
                                '1' => 1,
                                '2' => 2,
                                '3' => 3,
                                '4' => 4,
                            ),
                            'default' => '2',
                            'dependency' => array('mp_category_listing', '==', 'true'),
                        ),

                        // rl ( Reviews Lising ) -- Main Page
                        array(
                            'id' => 'mp_rl_heading',
                            'type' => 'subheading',
                            'content' => __('Review Listing', SCR_DOMAIN),
                            'dependency' => array('mp_review_listing', '==', 'true'),
                        ),

                        array(
                            'id' => 'mp_rl_title',
                            'type' => 'text',
                            'title' => __('Title', SCR_DOMAIN),
                            'default' => 'Review Listing',
                            'dependency' => array('mp_review_listing', '==', 'true'),
                        ),
                        array(
                            'id' => 'mp_rl_sortby',
                            'type' => 'select',
                            'chosen' => true,
                            'title' => __('Default Sort By', SCR_DOMAIN),
                            'placeholder' => __('Select a sortby option', SCR_DOMAIN),
                            'options' => array(
                                'alphabetical_asc' => __('Alphabetical Ascending', SCR_DOMAIN),
                                'alphabetical_desc' => __('Alphabetical Descending', SCR_DOMAIN),
                                'recent' => __('Recent', SCR_DOMAIN),
                                'updated' => __('Recently Updated', SCR_DOMAIN),
                                // 'num_of_reviews' => __('Number of Reviews, SCR_DOMAIN),
                            ),
                            'default' => 'recent',
                            'dependency' => array('mp_review_listing', '==', 'true'),
                        ),

                        array(
                            'id' => 'mp_rl_cols',
                            'type' => 'select',
                            'chosen' => true,
                            'title' => __('Num Of Columns', SCR_DOMAIN),
                            'placeholder' => __('Select an option', SCR_DOMAIN),
                            'options' => array(
                                '1' => 1,
                                '2' => 2,
                                '3' => 3,
                                '4' => 4,
                            ),
                            'default' => '3',
                            'dependency' => array('mp_review_listing', '==', 'true'),
                        ),
                    ),
                )
            );
        }
        public function general_settings($prefix)
        {
            $post_types = $this->get_post_types(['product']);

            \CSF::createSection(
                $prefix,
                array(

                    'id' => 'general_settings',
                    'title' => Translations::getStrings('GeneralSettings'),
                    'icon' => 'fa fa-cogs',
                    'fields' => array(

                        // array(
                        //     'id'          => 'template_source',
                        //     'type'        => 'select',
                        //     'title'       => 'Template Source',
                        //     'desc' => 'Select a Template Source',
                        //     'options'     => array(
                        //         'plugin'  => 'Plugin',
                        //         'theme'  => 'Theme',
                        //     ),
                        //     'default'     => 'theme'
                        // ),

                        // Select with CPT (custom post type) pages
                        // array(
                        //     'id' => 'review_enable_post-types', // removed @since v0.7.6
                        //     'type' => 'select',
                        //     'title' => __('Where to include reviews?', SCR_DOMAIN),
                        //     'chosen' => true,
                        //     'placeholder' => 'Select post types',
                        //     'options' => $post_types,
                        //     'multiple' => true,
                        //     'query_args' => array(
                        //         'post_type' => 'post',
                        //     ),
                        //     'default' => ['post'],
                        // ),

                        array(
                            'id' => 'author_review_enabled_post_types', // @since v0.7.6
                            'type' => 'select',
                            'title' => __('Where to include Author Reviews?', SCR_DOMAIN),
                            'chosen' => true,
                            'placeholder' => 'Select post types',
                            'options' => $post_types,
                            'multiple' => true,
                            'query_args' => array(
                                'post_type' => 'post',
                            ),
                            'default' => ['post'],
                        ),

                        /*** Note :- no need for this field. after adding the "author_review_enabled_post_types" field */
                        // array(
                        //     'id' => 'enable-author-review', // removed @since v0.7.6
                        //     'type' => 'switcher',
                        //     'title' => __('Enable author review', SCR_DOMAIN),
                        //     'default' => true,
                        // ),
                        array(
                            'id' => 'enable-pros-cons',
                            'type' => 'switcher',
                            'title' => __('Enable Pros and Cons', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'stats-subheading',
                            'type' => 'subheading',
                            'content' => __('Stats', SCR_DOMAIN),
                        ),

                        array(
                            'type' => 'submessage',
                            // 'style'   => 'info',
                            'content' => __("You can rate each of these stats in the edit post page(author rating). And if you have enabled 'user review', your users can rate them from the front end", SCR_DOMAIN),
                        ),

                        array(
                            'id' => 'stat-singularity',
                            'type' => 'select',
                            'title' => __('Single or Multiple Stat', SCR_DOMAIN),
                            'options' => array(
                                'single' => __('Single', SCR_DOMAIN),
                                'multiple' => __('Multiple', SCR_DOMAIN),
                            ),
                            'default' => 'single',
                        ),

                        array(
                            'type' => 'submessage',
                            'style' => 'info',
                            'content' => __('The first stat is always considered as the primary stat ( highlighted by blue color ), When you change from multiple stat to single stat values from the primary stat are used.', SCR_DOMAIN),
                            'dependency' => array('stat-singularity', '==', 'multiple'),
                        ),

                        array(
                            'id' => 'global_stats',
                            'type' => 'repeater',
                            'title' => __('Stats', SCR_DOMAIN),
                            'fields' => array(
                                array(
                                    'id' => 'stat_name',
                                    'type' => 'text',
                                    'placeholder' => __('Feature', SCR_DOMAIN),
                                ),
                            ),
                            'min' => 1,
                            'default' => array(
                                array(
                                    'stat_name' => 'Feature',
                                ),
                            ),
                            'dependency' => array('stat-singularity', '==', 'multiple'),
                        ),

                        array(
                            'id' => 'stats-type',
                            'type' => 'image_select',
                            'title' => __('Stats Type', SCR_DOMAIN),
                            'options' => array(
                                'star' => SCR_URL . 'includes/assets/img/stars-stat.png',
                                // 'bar' => SCR_URL . 'includes/assets/img/bars-stat.png',
                            ),
                            // 'desc' => 'choose between star and bars stats types',
                            'default' => 'star',
                        ),

                        // array(
                        //     'id' => 'stats-source-type',
                        //     'type' => 'select',
                        //     'title' => __('Source Type', SCR_DOMAIN),
                        //     'options' => array(
                        //         'icon' => __('Icon', SCR_DOMAIN),
                        //         'image' => __('Image', SCR_DOMAIN),
                        //     ),
                        //     // 'dependency' => array('stats-type', '==', 'star'),
                        //     'default' => 'icon',
                        // ),

                        array(
                            'id' => 'stats-show-rating-label',
                            'type' => 'switcher',
                            'title' => __('Show Rating Label', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'stats-icons',
                            'type' => 'icon_dropdown',
                            'title' => __('Icons', SCR_DOMAIN),
                            // 'dependency' => array('stats-source-type|stats-type', '==|==', 'icon|star'),
                            // 'dependency' => array('stats-source-type', '==', 'icon'),
                            'default' => 'star',
                        ),
                        array(
                            'id' => 'stats-icons-color',
                            'type' => 'color',
                            'title' => __('Icons Color', SCR_DOMAIN),
                            // 'dependency' => array('stats-source-type', '==', 'icon'),
                            'output' => array(
                                '.review-list .scr-icon:after',
                                '.review-list .scr-icon:after',
                                '.reviewed-list .scr-icon:after',
                                '.reviewed-list .scr-icon:after',
                            ),
                            'output_mode' => 'color',
                            'default' => '#e7711b',
                        ),
                        array(
                            'id' => 'stats-icons-label-color',
                            'type' => 'color',
                            'title' => __('Icons Label Color', SCR_DOMAIN),
                            // 'dependency' => array('stats-source-type', '==', 'icon'),
                            'output' => array('.review-list .reviewed-item .reviewed-item-label__score', '.review-list .reviewed-item .reviewed-item-label__score', '.reviewed-list .reviewed-item .reviewed-item-label__score', '.reviewed-list .reviewed-item .reviewed-item-label__score'),
                            'output_mode' => 'color',
                            'default' => '#0274be',
                        ),

                        // array(
                        //     'id' => 'stats-images',
                        //     'type' => 'fieldset',
                        //     'title' => __('Images', SCR_DOMAIN),
                        //     // 'dependency' => array('stats-source-type|stats-type', '==|==', 'image|star'),
                        //     'dependency' => array('stats-source-type', '==', 'image'),
                        //     'fields' => array(
                        //         array(
                        //             'id' => 'image',
                        //             'type' => 'media',
                        //             'title' => __('Image', SCR_DOMAIN),
                        //             'library' => 'image',
                        //             'placeholder' => 'http://',
                        //             'default' => [
                        //                 'url' => SCR_URL . 'includes/assets/img/tomato.png',
                        //                 'thumbnail' => SCR_URL . 'includes/assets/img/tomato.png',
                        //             ],
                        //         ),
                        //         array(
                        //             'id' => 'image-outline',
                        //             'type' => 'media',
                        //             'title' => __('Outline Image', SCR_DOMAIN),
                        //             'library' => 'image',
                        //             'placeholder' => 'http://',
                        //             'default' => [
                        //                 'url' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                        //                 'thumbnail' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                        //             ],
                        //         ),

                        //         array(
                        //             'type' => 'submessage',
                        //             'style' => 'info',
                        //             'content' => __('Image size below 50 * 50 is enough', SCR_DOMAIN),
                        //         ),
                        //     ),
                        // ),

                        // array(
                        //     'id'      => 'stats-bars-limit',
                        //     'title'   => 'Limit',
                        //     'type'    => 'slider',
                        //     'min'     => 5,
                        //     'max'     => 100,
                        //     'step'    => 5,
                        //     'unit'    => '%',
                        //     'default' => 100,
                        //     'desc' => 'Bar stat Limit b/w <b> 5 to 100 </b>',
                        //     'dependency' => array('stats-type', '==', 'bar'),
                        // ),

                        array(
                            'id' => 'stats-stars-limit',
                            'title' => __('Limit', SCR_DOMAIN),
                            'type' => 'slider',
                            'min' => 5,
                            'max' => 10,
                            'step' => 5,
                            'unit' => '#',
                            'default' => 5,
                            'desc' => __('Star stat scale b/w limit 5 to 10', SCR_DOMAIN),
                            // 'dependency' => array('stats-type', '==', 'star'),
                        ),

                        array(
                            'id' => 'stats-steps',
                            'type' => 'select',
                            'title' => __('Steps', SCR_DOMAIN),
                            'options' => array(
                                // 'precise' => 'Precise',
                                'half' => __('Half', SCR_DOMAIN),
                                'full' => __('Full', SCR_DOMAIN),
                            ),
                            'default' => 'half',
                        ),

                        array(
                            'id' => 'stats-animate',
                            'type' => 'switcher',
                            'title' => __('Stat Animate', SCR_DOMAIN),
                            'default' => false,
                        ),

                        array(
                            'id' => 'stats-no-rated-message',
                            'type' => 'text',
                            'title' => __('No rated message', SCR_DOMAIN),
                            'default' => 'Not Rated Yet !!!',
                        ),
                    ),
                )
            );
        }

        /* Single Post - Meta Data Options */
        public function single_post_meta_fields()
        {
            $post_types = $this->get_post_types([]);
            if (SCR_Getter::is_woocommerce_plugin_active()) {
                $post_types = array_merge($post_types, ['product' => 'Products']);
            }

            $prefix = SCR_POST_META;

            \CSF::createMetabox($prefix, array(
                'title' => __('Starcat Review', SCR_DOMAIN),
                'post_type' => array_keys($post_types),
                'show_restore' => true,
                'theme' => 'light',
            ));

            $this->single_post_features($prefix);
            $this->single_post_pros($prefix);
            $this->single_post_cons($prefix);
            $this->single_post_level_author_review_features($prefix); // @since v0.7.6
            $this->single_post_level_user_review_features($prefix); // @since v0.7.6

            /** tabbed view */
            // $this->single_review_settings($prefix);
            // $this->single_details($prefix);
            // $this->single_rich_snippets($prefix);
        }

        // Features - Main Settings Options
        public function single_post_settings_features($prefix, $parent = null)
        {
            \CSF::createSection($prefix, array(
                'parent' => $parent,
                'title' => __('Stats', SCR_DOMAIN),
                'icon' => 'fa fa-eye',
                'fields' => array(

                    array(
                        'id' => 'stats',
                        'type' => 'fieldset',
                        'title' => __('Features', SCR_DOMAIN),
                        'fields' => array(
                            array(
                                'id' => 'stats-list',
                                'type' => 'repeater',
                                'title' => __('Repeater', SCR_DOMAIN),
                                'fields' => array(

                                    array(
                                        'id' => 'stat_name',
                                        'type' => 'text',
                                        'title' => __('Stat Name', SCR_DOMAIN),
                                    ),
                                ),
                            ),
                        ),
                    ),

                ),
            ));
        }

        public function single_details($prefix, $parent = null)
        {
            $details_fields = Post_MetBox_Fields::single_details_fields();

            \CSF::createSection(
                $prefix,
                array(
                    'parent' => $parent,
                    'title' => __('Item Details', SCR_DOMAIN),
                    'icon' => 'fa fa-eye',
                    'fields' => $details_fields,
                )
            );
        }

        public function single_rich_snippets($prefix, $parent = null)
        {
            $fields = Post_MetBox_Fields::rich_snippets_fields();

            \CSF::createSection(
                $prefix,
                array(
                    'parent' => $parent,
                    'title' => __('Rich Snippets', SCR_DOMAIN),
                    'icon' => 'fa fa-eye',
                    'fields' => $fields,
                )
            );
        }

        public function single_post_pros($prefix, $parent = null)
        {

            $fields = Post_MetBox_Fields::single_post_prosandcons_fields('pros-list', 'Pros');

            \CSF::createSection($prefix, array(
                'parent' => $parent,
                'title' => __('Pros', SCR_DOMAIN),
                'icon' => 'fa fa-thumbs-up',
                'dependency' => array('enable-pros-cons', '==', 'true', 'true'),
                'fields' => $fields,
            ));
        }

        public function single_post_cons($prefix, $parent = null)
        {

            $fields = Post_MetBox_Fields::single_post_prosandcons_fields('cons-list', 'Cons');

            \CSF::createSection($prefix, array(
                'parent' => $parent,
                'title' => __('Cons', SCR_DOMAIN),
                'icon' => 'fa fa-thumbs-down',
                'fields' => $fields,
            ));
        }

        // Features - Stat Fields Meta Data Options
        public function single_post_features($prefix)
        {
            $list_of_stat_fields = $this->get_stat_fields();

            \CSF::createSection($prefix, array(
                'id' => 'stat',
                'title' => __('Stats', SCR_DOMAIN),
                'icon' => 'fa fa-th-list',
                'fields' => $list_of_stat_fields,
            ));
        }

        protected function get_stat_fields()
        {
            return array(

                array(
                    'id' => 'stats-list',
                    'type' => 'fieldset',
                    'fields' => $this->get_statlist_from_global(),
                ),

            );
        }

        protected function get_statlist_from_global()
        {
            $stats_list = [];

            $stats = SCR_Getter::get_global_stats();
            $singularity = SCR_Getter::get_stat_singularity();

            if (isset($stats) && !empty($stats)) {
                $stats_list[] = array(
                    'type' => 'submessage',
                    'content' => __('Author Review Stats List', SCR_DOMAIN),
                );
                $count = 0;
                foreach ($stats as $stat) {
                    if ($singularity == 'single' && $count >= 1) {
                        break;
                    }
                    if (is_string($stat)) {
                        $stat = ['stat_name' => $stat]; // Fix : On first installation undefined property
                    }
                    $stats_list[] = array(
                        'id' => strtolower($stat['stat_name']),
                        'type' => 'fieldset',
                        'fields' => array(
                            array(
                                'id' => 'stat_name',
                                'type' => 'text',
                                'title' => __('Stat Name', SCR_DOMAIN),
                                'attributes' => array(
                                    'readonly' => 'readonly',
                                ),
                                'default' => $stat['stat_name'],
                            ),

                            array(
                                'id' => 'rating',
                                'type' => 'slider',
                                'title' => __('Rating', SCR_DOMAIN),
                                'min' => 0,
                                'max' => 100,
                                'step' => 1,
                                'unit' => '%',
                                'default' => 0,
                            ),

                            array(
                                'type' => 'submessage',
                                'style' => 'success',
                                'content' => __('Rating 0 - 100', SCR_DOMAIN),
                            ),
                        ),
                    );

                    $count++;
                }
            }

            return $stats_list;
        }

        public function woocommerce_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'woocommerce_settings',
                    'title' => Translations::getStrings('WoocommerceSettings'),
                    'icon' => 'fa fa-shopping-cart',
                    'fields' => array(
                        array(
                            'id' => 'enable_reviews_on_woocommerce',
                            'type' => 'switcher',
                            'title' => __('Enable Starcat Reviews for Woocommerce', SCR_DOMAIN),
                            'default' => true,
                        ),
                        // array(
                        //     'id' => 'woo_enable_user_reviews',
                        //     'type' => 'switcher',
                        //     'title' => __('Enable Users Reviews for Woocommerce', SCR_DOMAIN),
                        //     'default' => true,
                        // ),
                        array(
                            'id' => 'woo_ur_who_can_review',
                            'type' => 'select',
                            'title' => __('Who Can Review', SCR_DOMAIN),
                            'desc' => __('Selecting Everyone will let even Non Logged-in Users add reviews', SCR_DOMAIN),
                            'options' => array(
                                'logged_in' => __('Logged In Users', SCR_DOMAIN),
                                'everyone' => __('Everyone', SCR_DOMAIN),
                            ),
                            'default' => 'logged_in',
                        ),
                        array(
                            'id' => 'woo_enable_pros_cons',
                            'type' => 'switcher',
                            'title' => __('Enable Pros and Cons', SCR_DOMAIN),
                            'default' => true,
                        ),
                        array(
                            'id' => 'woo_enable_voting',
                            'type' => 'switcher',
                            'title' => __('Voting', SCR_DOMAIN),
                            'default' => true,
                        ),
                        array(
                            'id' => 'woo_show_form_title',
                            'type' => 'switcher',
                            'title' => __('Show Form Title', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'woo_stats_subheading',
                            'type' => 'subheading',
                            'content' => __('Stats', SCR_DOMAIN),
                        ),

                        array(
                            'type' => 'submessage',
                            // 'style'   => 'info',
                            'content' => __("You can rate each of these stats in the edit post page(author rating). And if you have enabled 'user review', your users can rate them from the front end", SCR_DOMAIN),
                        ),

                        array(
                            'id' => 'woo_stat_singularity',
                            'type' => 'select',
                            'title' => __('Single or Multiple Stat', SCR_DOMAIN),
                            'options' => array(
                                'single' => __('Single', SCR_DOMAIN),
                                'multiple' => __('Multiple', SCR_DOMAIN),
                            ),
                            'default' => 'single',
                        ),

                        array(
                            'type' => 'submessage',
                            'style' => 'info',
                            'content' => __('The first stat is always considered as the primary stat ( highlighted by blue color ), When you change from multiple stat to single stat values from the primary stat are used.', SCR_DOMAIN),
                            'dependency' => array('woo_stat_singularity', '==', 'multiple'),
                        ),

                        array(
                            'id' => 'woo_global_stats',
                            'type' => 'repeater',
                            'title' => __('Stats', SCR_DOMAIN),
                            'fields' => array(
                                array(
                                    'id' => 'stat_name',
                                    'type' => 'text',
                                    'placeholder' => __('Feature', SCR_DOMAIN),
                                ),
                            ),
                            'min' => 1,
                            'default' => array(
                                array(
                                    'stat_name' => 'Feature',
                                ),
                            ),
                            'dependency' => array('woo_stat_singularity', '==', 'multiple'),
                        ),

                        // array(
                        //     'id' => 'woo_stats_source_type',
                        //     'type' => 'select',
                        //     'title' => __('Source Type', SCR_DOMAIN),
                        //     'options' => array(
                        //         'icon' => __('Icon', SCR_DOMAIN),
                        //         'image' => __('Image', SCR_DOMAIN),
                        //     ),
                        //     // 'dependency' => array('stats-type', '==', 'star'),
                        //     'default' => 'icon',
                        // ),

                        array(
                            'id' => 'woo_stats_show_rating_label',
                            'type' => 'switcher',
                            'title' => __('Show Rating Label', SCR_DOMAIN),
                            'default' => true,
                        ),

                        array(
                            'id' => 'woo_stats_icons',
                            'type' => 'icon_dropdown',
                            'title' => __('Icons', SCR_DOMAIN),
                            // 'dependency' => array('stats-source-type|stats-type', '==|==', 'icon|star'),
                            // 'dependency' => array('woo_stats_source_type', '==', 'icon'),
                            'default' => 'star',
                        ),

                        array(
                            'id' => 'woo_stats_icons_color',
                            'type' => 'color',
                            'title' => __('Icons Color', SCR_DOMAIN),
                            // 'dependency' => array('woo_stats_source_type', '==', 'icon'),
                            'output' => array(
                                '.review-list.woo-stats .review-item-stars .scr-icon:after',
                                '.review-list.woo-stats .scr-icon:after',
                                '.reviewed-list.woo-stats .scr-icon:after',
                                '.reviewed-list.woo-stats .scr-icon:after',
                            ),
                            'output_mode' => 'color',
                            'default' => '#e7711b',
                        ),
                        array(
                            'id' => 'woo_stats_icons_label_color',
                            'type' => 'color',
                            'title' => __('Icons Label Color', SCR_DOMAIN),
                            // 'dependency' => array('woo_stats_source_type', '==', 'icon'),
                            'output' => array('.review-list.woo-stats .reviewed-item .reviewed-item-label__score', '.review-list.woo-stats .reviewed-item .reviewed-item-label__score', '.reviewed-list.woo-stats .reviewed-item .reviewed-item-label__score', '.reviewed-list.woo-stats .reviewed-item .reviewed-item-label__score'),
                            'output_mode' => 'color',
                            'default' => '#0274be',
                        ),

                        // array(
                        //     'id' => 'woo_stats_images',
                        //     'type' => 'fieldset',
                        //     'title' => __('Images', SCR_DOMAIN),
                        //     // 'dependency' => array('stats-source-type|stats-type', '==|==', 'image|star'),
                        //     'dependency' => array('woo_stats_source_type', '==', 'image'),
                        //     'fields' => array(
                        //         array(
                        //             'id' => 'image',
                        //             'type' => 'media',
                        //             'title' => __('Image', SCR_DOMAIN),
                        //             'library' => 'image',
                        //             'placeholder' => 'http://',
                        //             'default' => [
                        //                 'url' => SCR_URL . 'includes/assets/img/tomato.png',
                        //                 'thumbnail' => SCR_URL . 'includes/assets/img/tomato.png',
                        //             ],
                        //         ),
                        //         array(
                        //             'id' => 'image-outline',
                        //             'type' => 'media',
                        //             'title' => __('Outline Image', SCR_DOMAIN),
                        //             'library' => 'image',
                        //             'placeholder' => 'http://',
                        //             'default' => [
                        //                 'url' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                        //                 'thumbnail' => SCR_URL . 'includes/assets/img/tomato-outline.png',
                        //             ],
                        //         ),

                        //         array(
                        //             'type' => 'submessage',
                        //             'style' => 'info',
                        //             'content' => __('Image size below 50 * 50 is enough', SCR_DOMAIN),
                        //         ),
                        //     ),
                        // ),

                        array(
                            'id' => 'woo_stats_steps',
                            'type' => 'select',
                            'title' => __('Steps', SCR_DOMAIN),
                            'options' => array(
                                // 'precise' => 'Precise',
                                'half' => __('Half', SCR_DOMAIN),
                                'full' => __('Full', SCR_DOMAIN),
                            ),
                            'default' => 'half',
                        ),

                        array(
                            'id' => 'woo_captcha_subheading',
                            'type' => 'subheading',
                            'content' => __('Google reCAPTCHA', SCR_DOMAIN),
                        ),

                        array(
                            'id' => 'woo_show_captcha',
                            'type' => 'switcher',
                            'title' => __('Show reCAPTCHA (v2 checkbox)', SCR_DOMAIN),
                            'default' => false,
                            'desc' => sprintf(__('Register for reCAPTCHA v2 at %s to get your site key and secret key. Make sure to add your domain name in the settings at the reCAPTCHA website. Read More at %s. %s reCAPTCHA v3 will not work, just v2. v3 will be added soon.', SCR_DOMAIN), '<a href="https://www.google.com/recaptcha">https://www.google.com/recaptcha</a>', '<a href="https://paupledocs.gitbook.io/starcat-documentation/">Starcat Reviews - Docs</a>', '<strong>' . __('Note', SCR_DOMAIN) . '</strong> : '),
                        ),

                        array(
                            'id' => 'woo_recaptcha_site_key',
                            'type' => 'text',
                            'title' => __('reCAPTCHA Site Key', SCR_DOMAIN),
                            'dependency' => array('woo_show_captcha', '==', 'true'),
                            'validate' => 'csf_validate_recaptcha_site_key',
                        ),

                        array(
                            'id' => 'woo_recaptcha_secret_key',
                            'type' => 'text',
                            'title' => __('reCAPTCHA Secret Key', SCR_DOMAIN),
                            'default' => '',
                            'dependency' => array('woo_show_captcha', '==', 'true'),
                            'validate' => 'csf_validate_recaptcha_secret_key',
                        ),
                    ),
                )
            );
        }

        public function get_post_types($excluded_post_types)
        {
            $options = array();
            $post_types = get_post_types(array('show_in_nav_menus' => true), 'objects');
            if (empty($post_types)) {
                return $options;
            }
            foreach ($post_types as $post_type) {
                if (!in_array($post_type->name, $excluded_post_types)) {
                    $options[$post_type->name] = $post_type->labels->name;
                }
            }

            return $options;
        }

        public function single_post_level_author_review_features($prefix)
        {
            $fields = $this->get_post_level_author_review_fields();

            \CSF::createSection($prefix, array(
                'id' => 'post_author_review_settings',
                'title' => __('Author Review Settings', SCR_DOMAIN),
                'icon' => 'fa fa-cogs',
                'fields' => $fields,
            ));
        }

        public function get_post_level_author_review_fields()
        {
            // user reviews settings URL
            $author_review_settings_page_url = admin_url("admin.php?page=scr-settings#tab=general-settings");
            $url_content = sprintf(__("Check Author Review Global Settings %s", SCR_DOMAIN), '<a href="' . $author_review_settings_page_url . '" target="_blank">' . __('here', SCR_DOMAIN) . '</a>');

            return array(
                array(
                    'id' => 'post_author_review_settings',
                    'type' => 'fieldset',
                    'fields' => array(
                        // array(
                        //     'type' => 'submessage',
                        //     'content' => __('Author Review Post Level Settings', SCR_DOMAIN),
                        // ),
                        array(
                            'id' => 'can_show_author_review',
                            'type' => 'select',
                            'title' => __('Display Author Review', SCR_DOMAIN),
                            'options' => array(
                                'apply_global_settings' => __('Apply Global Settings', SCR_DOMAIN),
                                'show' => __('Show', SCR_DOMAIN),
                                'dont_show' => __("Don't Show", SCR_DOMAIN),
                            ),
                            'default' => 'apply_global_settings',
                            'after' => $url_content,
                        ),
                        array(
                            'id' => 'custom_location',
                            'type' => 'switcher',
                            'title' => __('Enable Custom Location', SCR_DOMAIN),
                            'default' => false,
                            'dependency' => array(
                                array('can_show_author_review', 'any', 'apply_global_settings,show'),
                            ),
                        ),
                        array(
                            'id' => 'location',
                            'type' => 'select',
                            'title' => __('Location', SCR_DOMAIN),
                            'options' => array(
                                'after' => __('After the content', SCR_DOMAIN),
                                'before' => __('Before the content', SCR_DOMAIN),
                                'shortcode' => __('Shortcode', SCR_DOMAIN),
                            ),
                            'dependency' => array(
                                array('can_show_author_review', 'any', 'apply_global_settings,show'),
                                array('custom_location', '==', 'true'),
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'class' => 'scr_clipboard',
                            'default' => '[starcat_review_summary show_author_reviews_summary="1" show_user_reviews_summary="0" show_pros_and_cons_summary="1"]',
                            'after' => __('Copy Clipboard', SCR_DOMAIN),
                            // 'desc' => __('Copy and Paste this shortcode in the content', SCR_DOMAIN),
                            'dependency' => array(
                                array('can_show_author_review', 'any', 'apply_global_settings,show'),
                                array('custom_location', '==', 'true'),
                                array('location', '==', 'shortcode'),
                            ),
                            'attributes' => array(
                                'readonly' => 'readonly',
                            ),
                        ),
                    ),
                ),
            );
        }

        public function single_post_level_user_review_features($prefix)
        {
            $fields = $this->get_post_level_user_review_fields();

            \CSF::createSection($prefix, array(
                'id' => 'post_user_review_settings',
                'title' => __('User Review Settings', SCR_DOMAIN),
                'icon' => 'fa fa-cogs',
                'fields' => $fields,
            ));
        }

        public function get_post_level_user_review_fields()
        {
            // user reviews settings URL
            $global_settings_page_url = admin_url("admin.php?page=scr-settings#tab=user-reviews");

            // overrite the setting url, if current pags is product
            if (SCR_Getter::is_admin_product_page()) {
                $global_settings_page_url = admin_url("admin.php?page=scr-settings#tab=woocommerce-settings");
            }

            $url_content = sprintf(__("Check User Review Global Settings %s", SCR_DOMAIN), '<a href="' . $global_settings_page_url . '" target="_blank">' . __('here', SCR_DOMAIN) . '</a>');

            return array(
                array(
                    'id' => 'post_user_review_settings',
                    'type' => 'fieldset',
                    'fields' => array(
                        // array(
                        //     'type' => 'submessage',
                        //     'content' => __('User Review Post Level Settings', SCR_DOMAIN),
                        // ),
                        array(
                            'id' => 'can_show_user_review',
                            'type' => 'select',
                            'title' => __('Display User Review', SCR_DOMAIN),
                            'options' => array(
                                'apply_global_settings' => __('Apply Global Settings', SCR_DOMAIN),
                                'show' => __('Show', SCR_DOMAIN),
                                'dont_show' => __("Don't Show", SCR_DOMAIN),
                            ),
                            'after' => $url_content,
                            'default' => 'apply_global_settings',
                        ),
                        array(
                            'type' => 'subheading',
                            'content' => __("User Review Summary", SCR_DOMAIN),
                            'dependency' => array(
                                array('can_show_user_review', 'any', 'apply_global_settings,show'),
                            ),
                        ),
                        array(
                            'id' => 'custom_location',
                            'type' => 'switcher',
                            'title' => __('Enable Custom Location', SCR_DOMAIN),
                            'default' => false,
                            'dependency' => array(
                                array('can_show_user_review', 'any', 'apply_global_settings,show'),
                            ),
                        ),
                        array(
                            'id' => 'location',
                            'type' => 'select',
                            'title' => __('Location', SCR_DOMAIN),
                            'options' => array(
                                'after' => __('After the content', SCR_DOMAIN),
                                'before' => __('Before the content', SCR_DOMAIN),
                                'shortcode' => __('Shortcode', SCR_DOMAIN),
                            ),
                            'dependency' => array(
                                array('can_show_user_review', 'any', 'apply_global_settings,show'),
                                array('custom_location', '==', 'true'),
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'class' => 'scr_clipboard',
                            'default' => '[starcat_review_summary show_author_reviews_summary="0" show_user_reviews_summary="1" show_pros_and_cons_summary="0"]',
                            'after' => __('Copy Clipboard', SCR_DOMAIN),
                            // 'desc' => __('Copy and Paste this shortcode in the content', SCR_DOMAIN),
                            'dependency' => array(
                                array('can_show_user_review', 'any', 'apply_global_settings,show'),
                                array('custom_location', '==', 'true'),
                                array('location', '==', 'shortcode'),
                            ),
                            'attributes' => array(
                                'readonly' => 'readonly',
                            ),
                        ),
                    ),
                ),
            );
        }

        public function single_review_settings($prefix)
        {
            $author_reviews_fields = $this->get_post_level_author_review_fields();
            $user_review_fields = $this->get_post_level_user_review_fields();

            \CSF::createSection($prefix, array(
                'title' => __('Review Settings', SCR_DOMAIN),
                'icon' => 'fa fa-cogs',
                'fields' => array(
                    array(
                        'id' => 'post_review_settings',
                        'type' => 'tabbed',
                        'tabs' => array(
                            array(
                                'title' => __('Author Review Settings', SCR_DOMAIN),
                                'icon' => 'fa fa-star',
                                'fields' => $author_reviews_fields,
                            ),
                            array(
                                'title' => __('User Review Settings', SCR_DOMAIN),
                                'icon' => 'fa fa-star',
                                'fields' => $user_review_fields,
                            ),
                        ),
                    ),
                ),
            ));
        }

    } // END CLASS
}
