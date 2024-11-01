<?php

namespace StarcatReview\App\Views\Blocks;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Blocks\Card')) {
    class Card
    {
        /* HTML for Single Card */
        public function get_view($item)
        {
            $this->props = $this->interprete_input_props($item);

            $html = '';

            $col_classes = "col-xs-12 col-lg-" . $this->props['col-lg'];
            $html .= '<div class="scr-collection__col item ' . $col_classes . '">'; // can't add additional classes
            $html .= '<div class="scr-review-card" >';

            if ($this->show_item('title')) {
                $html .= '<div class="review-card__header">' . __($item['title'], SCR_DOMAIN) . '</div>';
            }

            // error_log('meta_data : ' . print_r($item['meta_data'], true));
            if ($this->show_item('title')) {
                $html .= '<img src="' . $item['featured_image'] . '"/>';
            }

            if ($this->show_item('content')) {
                $pre_content_html = isset($item['pre_content_html']) ? $item['pre_content_html'] : '';
                $html .= '<div class="review-card__body">' . $pre_content_html . $item['content'] . '</div>';
            }

            if ($this->show_item('link')) {
                $html .= '<div class="review-card__footer"><a class="button" href="' . $item['url'] . '">' . __('See More', SCR_DOMAIN) . '</a></div>';
            }
            if (isset($item['meta_data']['review_count'])) {
                $html .= '<span class="reviewCount"  data-reviewCount="' . $item['meta_data']['review_count'] . '"></span>';
            }
            if (isset($item['meta_data']['date'])) {
                $html .= '<span class="postDate"   data-postDate="' . $item['meta_data']['date'] . '"></span>';
            }
            if (isset($item['meta_data']['modified_date'])) {
                $html .= '<span class="postModified"   data-postModified="' . $item['meta_data']['modified_date'] . '"></span>';
            }
            if (isset($item['meta_data']['trendScore'])) {
                $html .= '<span class="trendScore"   data-trendScore="' . $item['meta_data']['trendScore'] . '"></span>';
            }
            if (isset($item['meta_data']['positiveScore'])) {
                $html .= '<span class="positiveScore"   data-positiveScore="' . $item['meta_data']['positiveScore'] . '"></span>';
            }

            $html .= '</div>';
            $html .= '</div>';

            return $html;
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
