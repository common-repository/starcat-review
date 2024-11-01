<?php

namespace StarcatReview\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Actions')) {
    class Actions
    {
        public function __construct()
        {
            // add_action('pre_get_comments', [$this, 'exclude_from_comments']);
        }

        /*
        Comment Type of SCR_POST_TYPE is exclude from standard comments list
        Unless we call explicitly by get_comments($type = "starcat_review")
         */
        public function exclude_from_comments(\WP_Comment_Query $query)
        {
            /* only allow SCR_POST_TYPE when is required explicitly */

            if ($query->query_vars['type'] !== SCR_COMMENT_TYPE) {
                $query->query_vars['type__not_in'] = array_merge(
                    (array) $query->query_vars['type__not_in'],
                    array(SCR_COMMENT_TYPE)
                );
            }
        }

    }
}
