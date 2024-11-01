<?php

namespace StarcatReview\App\Components\ProsAndCons;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\ProsAndCons\View')) {
    class View
    {
        public function get($viewProps)
        {
            $this->items = $viewProps['items'];

            // Return '' if pros and cons are empty
            if ($this->is_empty()) {
                return '';
            }

            $html = "<div class='prosandcons'>";
            $html .= "<h6 class='ui header'>" . __('Pros & Cons', SCR_DOMAIN) . "</h6>";
            $html .= "<div class='items-container'>";

            $html .= $this->get_list('pros', $this->items['pros']);
            $html .= $this->get_list('cons', $this->items['cons']);

            $html .= "</div>";

            $html .= "</div>";

            return $html;
        }

        // User review Form fields
        public function get_fields($props)
        {
            $this->collection = $props['collection'];
            $html = '';
            // $html .= '<div class="two fields">';
            $html .= $this->get_fields_of('pros', $props);
            $html .= $this->get_fields_of('cons', $props);
            // $html .= '</div>';

            return $html;
        }

        protected function get_list($name, $items)
        {
            $icon_class = ($name === 'pros') ? 'green thumbs up icon' : 'red thumbs down icon';

            $html = "<ul class='" . $name . "'>";

            for ($ii = 0; $ii < sizeof($items); $ii++) {
                if (!empty($items[$ii])) {
                    $html .= "<li><i class='" . $icon_class . "'></i>" . $items[$ii] . "</li>";
                }
            }

            $html .= "</ul>";

            return $html;
        }

        protected function get_fields_of($name, $props)
        {
            // error_log('prosandcons props : ' . print_r($props, true));

            $options = isset($props['items']) && !empty($props['items']) ? $props['items'] : [];
            // $fields = isset($props['fields']) && !empty($props['fields']) ? $props['fields'] : [];

            $optionsHTML = $this->get_field($name, $props['items'], 0, '');
            // if (isset($fields[$name]) && !empty($fields[$name])) {
            //     $optionsHTML = '';
            //     for ($ii = 0; $ii < sizeof($fields[$name]); $ii++) {
            //         $optionsHTML .= $this->get_field($name, $options, $ii, $fields[$name][$ii]);
            //     }
            // }

            $options = $data_props = htmlspecialchars(json_encode($this->collection[$name . '-list']), ENT_QUOTES, 'UTF-8');

            $html = '<div class="field review-' . $name . '-repeater" data-' . $name . '="' . $options . '">';
            // $html .= '<div class="ui segment">';
            $html .= '<h5> ' . __(ucfirst($name), SCR_DOMAIN) . ' </h5>';
            $html .= '<div data-repeater-list="' . $name . '" >';
            $html .= $optionsHTML;
            $html .= '</div>';
            $html .= '<div data-repeater-create class="ui icon basic button mini"><i class="plus icon"></i></div>';
            // $html .= '</div>';
            $html .= '</div>';

            return $html;
        }

        protected function get_field($name, $options, $ii, $value)
        {
            $html = '';
            $html .= '<div class="unstackable fields" data-repeater-item >';
            $html .= '<div class="fourteen wide field">';
            $html .= '<select class="ui fluid search prosandcons dropdown mini" name="' . $name . '[' . $ii . ']" data-' . $name . '="' . $name . '">';
            $html .= $this->get_options($name, $options, $value);
            $html .= '</select>';
            $html .= '</div>';
            $html .= '<div class="two wide field">';
            $html .= '<div class="ui icon basic button mini" data-repeater-delete><i class="minus icon"></i></div>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }

        private function get_options($name, $options, $data)
        {
            // default option value or sometimes field placeholder
            $html = '<option value=""> ' . __('Type new or select existing ' . $name, SCR_DOMAIN) . ' </option>';

            if (!empty($data)) {
                $html = $this->get_option($data);
            }
            $options = $this->collection[$name . '-list'];

            if (isset($options) && !empty($options)) {
                foreach ($options as $option) {
                    $html .= $this->get_option($option['item']);
                }
            }

            return $html;
        }

        private function get_option($value)
        {
            return '<option value="' . strtolower(preg_replace('/\s+/', '_', $value)) . '"> ' . $value . '</option>';
        }

        private function is_empty()
        {
            $is_empty = true;

            if (!isset($this->items) || empty($this->items)) {
                return $is_empty;
            }

            $is_pros_empty = (!isset($this->items['pros']) || empty($this->items['pros']));
            $is_cons_empty = (!isset($this->items['cons']) || empty($this->items['cons']));

            // Either should be NOT EMPTY
            if (!$is_pros_empty || !$is_cons_empty) {
                $is_empty = false;
            }

            return $is_empty;
        }
    } // END CLASS
}
