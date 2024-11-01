<?php

namespace StarcatReview\App\Views\Blocks;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Blocks\Enhanced_Card')) {
    class Enhanced_Card
    {

        public function __construct()
        { }

        public function get_view($item)
        {
            $this->props = $this->interprete_input_props($item);
            $html = '';

            $col_classes = "col-xs-12 col-lg-" . $this->props['col-lg'];

            $html .= '<div class="scr-collection__col item ' . $col_classes . '">'; // can't add additional classes
            $html .= '<div class="scr-review-card ui comment">';
            $html .= '<a class="avatar"> ' . $item['avatar'] . ' </a>';
            $html .= ' <div class="content">
                        <a class="author">' . $item['author'] . '</a>
                        <div class="metadata">
                            <div class="date"> ' . $item['date'] . ' </div>
                        </div>';
            $html .= '<div class="text">';

            $parts = $this->get_parts($item);
            foreach ($this->props['html_parts'] as $key => $html_part) {

                // If $html_content is a key of html_part
                if (array_key_exists($html_part, $parts)) {
                    $html .= $parts[$html_part];
                } else {
                    // If $html_content is a html
                    $html .= $html_part;
                }
            }
            $html .= '<span class="reviewCount"  data-reviewcount="' . $item['reviews'] . '"></span>';

            $html .= '</div>';
            $html .= '</div>
            </div></div>';

            return $html;
        }

        public function get_parts($item)
        {
            $parts = [
                'title' => '<div class="review-card__header">' . $item['title'] . '</div>',
                'content' => '<div class="review-card__body">' . $item['content'] . '</div>',
                'footer' => '<div class="review-card__footer"><a href="' . $item['url'] . '">See all >> </a></div>',
                'reviewCount' => '<span class="reviewCount"  data-reviewcount="' . $item['reviews'] . '"></span>'
            ];

            return $parts;
        }

        protected function show_item($itemName)
        {

            if (in_array($itemName, $this->props['items_display'])) {
                return true;
            }

            return false;
        }

        protected function interprete_input_props($input_props)
        {
            $props = $input_props;
            $props['col-lg'] = 12 / $input_props['columns'];

            return $props;
        }
    } // END CLASS
}
