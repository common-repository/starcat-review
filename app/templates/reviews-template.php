<?php
/**
 * Display single product reviews for StarCat Review
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * undocumented class
 */
class reviews_template
{
    public function __construct()
    {
        echo $this->view();
    }

    public function view()
    {
        $review_builder = new \StarcatReview\App\Builders\Review_Builder();
        $post_level_settings = new \StarcatReview\App\Post_Settings\Post_Level_Settings();
        $post_settings_args = $post_level_settings->get_author_and_user_reviews_settings();
        $summay_args = $post_level_settings->get_summary_args_by_post_settings($post_settings_args);
        $review_summary_content = $review_builder->get_summary_content($summay_args['after']);
        $review_form_and_list_content = $review_builder->get_reviews();
        return $review_summary_content . $review_form_and_list_content;
    }
}

new reviews_template();