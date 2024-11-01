<?php

namespace StarcatReview\App\Components\Comparison;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Comparison\Model')) {
    class Model
    {

        /*
        Data Model
        $stats = [
        'stat_name_1' => [
        'post_id_1' => 'post_id_1_value',
        'post_id_2' => 'post_id_2_value',
        'post_id_3' => 'post_id_3_value',
        ],
        'stat_name_2 => [
        'post_id_1' => 'post_id_1_value',
        'post_id_2' => 'post_id_2_value',
        'post_id_3' => 'post_id_3_value',
        ],
        ];

        $stats = [
        'stat_name_1' => [
        'post_id_1' => 'post_id_1_value',
        'post_id_2' => 'post_id_2_value',
        'post_id_3' => 'post_id_3_value',
        ],
        'stat_name_2 => [
        'post_id_1' => 'post_id_1_value',
        'post_id_2' => 'post_id_2_value',
        'post_id_3' => 'post_id_3_value',
        ],
        ];
         */
        public function get($args)
        {
            $stat_cols = array();
            foreach ($args['posts'] as $key => $post) {
                //$stats_list = $this->get_stats_list($post->ID);
                $stat_column_list = $this->get_feature_columns($post['stats']);
                // error_log("stat_column_list" . print_r($stat_column_list, true));
                if (count($stat_column_list) > 0) {
                    foreach ($stat_column_list as $stat_col) {
                        if (!in_array($stat_col, $stat_cols)) {
                            $stat_cols[] = $stat_col;
                        }
                    }
                }
                $args['posts'][$key]['cols'] = $stat_column_list;
            }
            // error_log("args" . print_r($args, true));
            // $get_overall_stat_features = $this->get_overall_features($args['posts']);
            $args['cols'] = count($stat_cols) > 0 ? $stat_cols : array();

            return $args;
        }

        protected function get_feature_columns($args)
        {
            $stat_columns = array();
            $stat_columns[] = "scr-ratings";
            if (count($args) > 0) {
                //get stat features only
                $get_stat_columns = array_keys($args);
                // error_log("get_stat_columns" . print_r($get_stat_columns, true));
                foreach ($get_stat_columns as $stat_name) {
                    if (!in_array($stat_name, $stat_columns)) {
                        $stat_columns[] = $stat_name;
                    }
                }
            }
            return $stat_columns;
        }

        protected function get_stats_list($post_id)
        {
            $review_post_meta = get_post_meta($post_id, SCR_POST_META, true);

            // Return if empty
            if (!isset($review_post_meta['stats']) || empty($review_post_meta['stats'])) {
                return [];
            }

            $stats_list = $review_post_meta['stats']['stats-list'];

            return $stats_list;
        }
    } // END CLASS

}
