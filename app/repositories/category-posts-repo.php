<?php

namespace StarcatReview\App\Repositories;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Repositories\Category_Posts_Repo')) {
    class Category_Posts_Repo
    {

        private $last_query_post_count = 0;

        public function get_category_posts($args)
        {

            // error_log('Category_Posts_Repo -> get_category_posts');
            $input_args = [
                'posts_per_page' => 10,
                'term_id' => $args['term_id']
            ];
            $args = $this->get_args($input_args);

            $posts = [];

            $query = new \WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    // Optionally, pick parts of the post and create a custom collection.
                    $query->the_post();
                    $posts[] = get_post();
                }
                wp_reset_postdata();
            }
            $this->last_query_post_count = $query->found_posts;

            // error_log('$posts : ' . print_r($posts, true));
            return $posts;
        }

        public function get_last_query_post_count()
        {
            return $this->last_query_post_count;
        }

        public function get_args($input_args)
        {
            // error_log('$input_args : ' . print_r($input_args, true));
            // $term = get_queried_object();
            // the query to set the posts per page to 3
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'posts_per_page' => $input_args['posts_per_page'] ? $input_args['posts_per_page'] : -1,
                'post_type' => SCR_POST_TYPE,
                'paged' => $paged,
                'tax_query' => array(
                    array(
                        'taxonomy' => SCR_CATEGORY,
                        'field'    => 'id',
                        'terms'    => $input_args['term_id'],
                    ),
                )
            );

            return $args;
        }
    } // END CLASS
}
