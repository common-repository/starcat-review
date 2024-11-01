<?php

namespace StarcatReview\Includes\Utils;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Utils\Post')) {
    class Post
    {
        public function insert_term_with_post($post_type, $term_value, $taxonomy, $post_title = 'random', $post_content = 'demo text', $parent_term_id = 0)
        {
            if (!term_exists($term_value, $taxonomy, $parent_term_id)) {
                // echo "parent_term_id: " . $parent_term_id;
                $term_info = wp_insert_term($term_value, $taxonomy, array('parent' => $parent_term_id));
                $term_id = $term_info['term_id'];
            } else {
                $term = get_term_by('slug', $term_value, $taxonomy);
                $term_id = $term->term_id;
            }

            $post_id = wp_insert_post(array('post_title' => $post_title, 'post_type' => $post_type, 'post_content' => $post_content, 'post_status' => 'publish'));

            $cat_ids = array_map('intval', (array) $term_id);
            $cat_ids = array_unique($cat_ids);
            wp_set_object_terms($post_id, $cat_ids, $taxonomy);
            return [$post_id, $term_id];
        }

        public function insert_post_to_child_term($post_type, $term_value, $taxonomy, $parent_term)
        {

            $term_info = wp_insert_term($term_value, $taxonomy, array('parent' => $parent_term));
            $term_id = $term_info['term_id'];
            $post_id = wp_insert_post(array('post_title' => 'random', 'post_type' => $post_type, 'post_content' => 'demo text', 'post_status' => 'publish'));

            $cat_ids = array_map('intval', (array) $term_id);
            $cat_ids = array_unique($cat_ids);
            wp_set_object_terms($post_id, $cat_ids, $taxonomy);

            return [$post_id, $term_id];
        }

        public function insert_post_with_term($post_type, $term_id, $taxonomy, $post_title = 'random', $post_content = 'demo text')
        {
            $post_id = wp_insert_post(array('post_title' => $post_title, 'post_type' => $post_type, 'post_content' => $post_content, 'post_status' => 'publish'));
            $cat_ids = array_map('intval', (array) $term_id);
            $cat_ids = array_unique($cat_ids);
            wp_set_object_terms($post_id, $cat_ids, $taxonomy);
            return $post_id;
        }
    } // END CLASS
}
