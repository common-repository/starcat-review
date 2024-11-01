<?php

namespace StarcatReview\Includes\Utils;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

if (!class_exists('\StarcatReview\Includes\Utils\Test_Helpers')) {
    class Test_Helpers
    {
        public function __construct()
        {
            $this->users = new \StarcatReview\Includes\Utils\Users();
            $this->post = new \StarcatReview\Includes\Utils\Post();
        }

        /* Execute Private / Protected methods */
        public static function executeMethod($methodName, $className, $args = [])
        {
            $class = new \ReflectionClass($className);
            $method = $class->getMethod($methodName);
            $method->setAccessible(true);
            $obj = new $className();
            $term_info = $method->invokeArgs($obj, $args);
            return $term_info;
        }

        public function create_new_user($role = 'subscriber', $username = 'subman', $password = 'subpass', $email = 'submail@pauple.com')
        {
            return $this->users->create_new_user($role, $username, $password, $email);
        }

        public function get_page_title_for_slug($page_slug)
        {
            $page = get_page_by_path($page_slug, OBJECT);
            $title = (isset($page)) ? $page->post_title : false;
            return $title;
        }

        public function does_page_exist($page_title)
        {
            $page = get_page_by_title($page_title);
            return (isset($page) && !empty($page)) ? true : false;
        }

        public function insert_term_with_post($post_type, $term_value, $taxonomy, $post_title = 'random', $post_content = 'demo text', $parent_term_id = 0)
        {
            return $this->post->insert_term_with_post($post_type, $term_value, $taxonomy, $post_title, $post_content, $parent_term_id);
        }

        public function insert_post_to_child_term($post_type, $term_value, $taxonomy, $parent_term)
        {
            return $this->post->insert_post_to_child_term($post_type, $term_value, $taxonomy, $parent_term);
        }

        public function insert_post_with_term($post_type, $term_id, $taxonomy, $post_title = 'random', $post_content = 'demo text')
        {
            return $this->post->insert_post_with_term($post_type, $term_id, $taxonomy, $post_title, $post_content);
        }

    } // END CLASS
}
