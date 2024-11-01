<?php

namespace StarcatReview\App\Components\Listing;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Listing\Model')) {
    class Model
    {

        public function __construct()
        {
            $this->cat_posts_repo = new \StarcatReview\App\Repositories\Category_Posts_Repo();
        }

        public function get_viewProps($args)
        {
            $collectionProps = $this->get_collection_props($args);
            $itemsProps = $args['items'];
            $viewProps = array(
                'collection' => $collectionProps,
                'items' => $itemsProps,
            );


            return $viewProps;
        }

        protected function get_collection_props($args)
        {
            // error_log('get_collection_props $args : ' . print_r($args, true));
            $post_count = $this->get_posts_count();

            $posts_per_page = isset($args['posts_per_page']) ? $args['posts_per_page'] : 9;
            $collectionProps = [
                'title' => '',
                'posts_per_page' =>  $posts_per_page,
                'show_controls' => [
                    'search' => isset($args['show_search']) ? $args['show_search'] : true,
                    'sort' =>  isset($args['show_sortBy']) ? $args['show_sortBy'] : true,
                    // 'reviews' => $args['show_num_of_reviews_filter'],
                ],
                'post_count' => $post_count,
                'total_pages' => $post_count / $posts_per_page,
                'pagination' => true,
                'columns' => isset($args['num_of_cols']) ? $args['num_of_cols'] : 3,
                'items_display' => isset($args['items_display']) ? $args['items_display'] : ['title', 'content', 'link']
            ];

            if (!isset($args['show_controls']) || $args['show_controls'] == false) {
                $collectionProps['show_controls'] = $args['show_controls'];
            }

            return $collectionProps;
        }

        protected function get_posts_count()
        {
            return $this->cat_posts_repo->get_last_query_post_count();
        }
    } // END CLASS
}
