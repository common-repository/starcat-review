<?php

namespace StarcatReview\App\Services\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Services\Shortcodes\Builder_Fields')) {
    class Builder_Fields
    {

        public function get_post_field()
        {
            return [
                'id' => 'post_id',
                'type' => 'text',
                'title' => __('Enter the Post ID (or) Page ID', 'starcat-review'),
            ];
        }

        public function get_show_stats_field()
        {
            return [
                'id' => 'show_stats',
                'type' => 'switcher',
                'title' => __('Show User Review Stats', 'starcat-review'),
                'default' => true,
            ];
        }

        public function get_show_form_field()
        {
            return [
                'id' => 'show_form',
                'type' => 'switcher',
                'title' => __('Show User Review Form', 'starcat-review'),
                'default' => true,
            ];
        }

        public function get_show_lists_field()
        {
            return [
                'id' => 'show_lists',
                'type' => 'switcher',
                'title' => __('Show User Review Lists', 'starcat-review'),
                'default' => true,
            ];
        }

        public function get_show_summary_field()
        {
            return [
                'id' => 'show_summary',
                'type' => 'switcher',
                'title' => __('Show Review Summary', 'starcat-review'),
                'default' => true,
            ];
        }

        public function get_show_author_reviews_summary_field(array $dependencies = array())
        {
            $field = [
                'id' => 'show_author_reviews_summary',
                'type' => 'switcher',
                'title' => __('Show Author Reviews Summary', 'starcat-review'),
                'default' => true,
            ];

            $field = $this->set_dependencies($field, $dependencies);
            return $field;
        }

        public function get_show_user_reviews_summary_field(array $dependencies = array())
        {
            $field = [
                'id' => 'show_user_reviews_summary',
                'type' => 'switcher',
                'title' => __('Show Users Reviews Summary', 'starcat-review'),
                'default' => true,
            ];
            $field = $this->set_dependencies($field, $dependencies);
            return $field;
        }

        public function get_show_pros_and_cons_field(array $dependencies = array())
        {
            $field = [
                'id' => 'show_pros_and_cons_summary',
                'type' => 'switcher',
                'title' => __('Show Author Pros and Cons Summary', 'starcat-review'),
                'default' => true,
            ];
            $field = $this->set_dependencies($field, $dependencies);
            return $field;
        }

        public function get_show_review_title_field(array $dependencies = array())
        {
            $field = [
                'id' => 'show_review_title',
                'type' => 'switcher',
                'title' => __('Show Title', 'starcat-review'),
                'default' => true,
            ];

            $field = $this->set_dependencies($field, $dependencies);
            return $field;
        }

        public function get_show_review_search_field(array $dependencies = array())
        {
            $field = [
                'id' => 'show_review_search',
                'type' => 'switcher',
                'title' => __('Show Search', 'starcat-review'),
                'default' => true,
            ];

            $field = $this->set_dependencies($field, $dependencies);
            return $field;
        }

        public function get_show_review_sort_field(array $dependencies = array())
        {
            $field = [
                'id' => 'show_review_sort',
                'type' => 'switcher',
                'title' => __('Show Sort', 'starcat-review'),
                'default' => true,
            ];
            $field = $this->set_dependencies($field, $dependencies);
            return $field;
        }

        public function get_heading_content_field(string $content, array $dependencies = array())
        {
            $field = [
                'type' => 'subheading',
                'content' => $content,
            ];
            $field = $this->set_dependencies($field, $dependencies);
            return $field;
        }

        public function set_dependencies(array $field, array $dependencies = array())
        {

            if (empty($dependencies)) {
                return $field;
            }

            foreach ($dependencies as $dependency) {
                $field['dependency'] = [
                    $dependency['field_id'],
                    $dependency['operand'],
                    $dependency['value'],
                ];
            }

            return $field;
        }

    }
}
