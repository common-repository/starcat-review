<?php

namespace StarcatReview\Includes\Update;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * Helpie upgrades.
 *
 * Helpie upgrades handler class is responsible for updating different
 * Helpie versions.
 *
 * @since 1.9
 */
if (!class_exists('\StarcatReview\Includes\Update\Upgrades')) {
    class Upgrades
    {

        public static function init()
        {
            // error_log('Upgrades->init()');
            self::removed_old_upgrader_slug();

            $args = [
                'db_version' => get_option('starcat_review_version'),
                'file_version' => SCR_VERSION,
                'version_option' => 'starcat_review_version',
                'slug' => 'starcat_review',
            ];

            $upgrades_list = new \StarcatReview\Includes\Update\Upgrades_List();

            $upgrader = new \Pauple\Pluginator\Upgrader($args, $upgrades_list);
            $upgrader::add_actions();

        }

        public static function removed_old_upgrader_slug()
        {

            $old_upgrades = get_option('slug_upgrades');
            $old_version = get_option('SCR_VERSION');

            if (!empty($old_upgrades)) {
                update_option('starcat_review_upgrades', $old_upgrades);
                delete_option('slug_upgrades');
            }

            if (!empty($old_version)) {
                update_option('starcat_review_version', $old_version);
                delete_option('SCR_VERSION');
            }

            return;
        }

    } // END CLASS
}
