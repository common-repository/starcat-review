<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('\Starcat_Review')) {
    class Starcat_Review
    {
        public function __construct()
        {
            $this->setup_autoload();
            $this->load_library();
            $this->load_scr_functions();
            $this->load_hooks();
            $this->load_update_handler();
            // These components will handle the hooks internally, no need to call this in a hook
            $this->load_components();

        }

        public function setup_autoload()
        {
            require_once SCR_PATH . '/vendor/autoload.php';
        }

        public function load_library()
        {
            if (!class_exists("\Pauple\Pluginator\Library")) {
                wp_die("\"freemius/wordpress-sdk\" and \"Codestar Framework\" library was not installed, \"Tablesome\" is depend on it. Do run \"composer update\".");
            }

            $library = new \Pauple\Pluginator\Library();
            $library::register_libraries(['codestar', 'freemius']);
        }

        public function load_scr_functions()
        {
            /* Require functions.php */
            require_once SCR_PATH . 'includes/functions.php';
        }

        public function load_hooks()
        {
            new \StarcatReview\Includes\Hooks();
            register_activation_hook(SCR__FILE__, [$this, 'activate_plugin_name']);
            register_deactivation_hook(SCR__FILE__, [$this, 'deactivate_plugin_name']);
        }

        public function load_update_handler()
        {
            /* Upgrades */
            $upgrades = new \StarcatReview\Includes\Update\Upgrades();
            $upgrades::init();
        }

        public function load_components()
        {
            /* settings getter */
            require_once SCR_PATH . 'includes/settings/getter.php';

            $settings = new \StarcatReview\Includes\Settings();

            $shortcode_builder = new \StarcatReview\App\Services\Shortcodes\Builder();
            $shortcode_builder->init();

            // Dashboard User review Table
            require_once SCR_PATH . '/app/components/user-reviews/table.php';

            /* New Features */
            $Non_Logged_In_User = new \StarcatReview\Features\Non_Logged_In_User();

            /* Recaptcha */
            $recaptcha = new \StarcatReview\Services\Recaptcha();

            // Developement Purpose Only to add add-ons without activate and install
            // require_once SCR_PATH . 'vendor/pauple/starcat-review-cpt/starcat-review-cpt.php';
            // require_once SCR_PATH . 'vendor/pauple/starcat-review-photo-reviews/starcat-review-photo-reviews.php';
            // require_once SCR_PATH . 'vendor/pauple/starcat-review-ct/starcat-review-ct.php';

            // require_once SCR_PATH . 'vendor/pauple/starcat-review-woo-notify/starcat-review-woo-notify.php';

        }

        public static function activate_plugin_name($network_wide)
        {
            if ($network_wide) { //Plugin is network activated
                $site_ids = get_sites(array('fields' => 'ids'));
                foreach ($site_ids as $site_id) {
                    //Perform something on all sites within the network
                    switch_to_blog($site_id);
                    add_option(SCR_PLUGIN_BASE, true);
                    // error_log('!!! Multi Site Plugin Activation Done !!!');
                    restore_current_blog();
                }
                return;
            }

            // error_log('!!! Single Site Plugin Activation Done !!!');
            add_option(SCR_PLUGIN_BASE, true);
        }

        public static function deactivate_plugin_name($network_wide)
        {
            if ($network_wide) { //Plugin is network activated
                $site_ids = get_sites(array('fields' => 'ids'));
                foreach ($site_ids as $site_id) {
                    //Perform something on all sites within the network
                    switch_to_blog($site_id);
                    delete_option(SCR_PLUGIN_BASE);
                    // error_log('!!! Multi Site Plugin Deactivation Done !!!');
                    restore_current_blog();
                }
                return;
            }

            // error_log('!!! Single Site Plugin Deactivation Done !!!');
            delete_option(SCR_PLUGIN_BASE);
        }

    } // END CLASS
}

new Starcat_Review();
