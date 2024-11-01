<?php

namespace StarcatReview\App\Views\Rating_Types;

use \StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Rating_Types\Star_Rating')) {
    class Star_Rating
    {
        public function __construct($viewProps)
        {
            $this->props = $viewProps;
            // error_log('viewProps : ' . print_r($viewProps, true));
        }

        public function get_view()
        {
            $html = '';
            $woo_stats_class = SCR_Getter::is_single_product_post() || SCR_Getter::is_admin_product_page() ? 'woo-stats' : '';
            if (isset($this->props['items']) && !empty($this->props['items'])) {

                $html .= '<ul class="reviewed-list ' . $woo_stats_class . '"
                data-animate="' . $this->props['collection']['animate'] . '"
            >';
                foreach ($this->props['items'] as $key => $stat) {
                    $html .= $this->get_reviewed_stat($key, $stat['rating'], $stat['score']);
                }

                $html .= '</ul>';
            }

            return $html;
        }




        protected function get_row_of_icons($score)
        {
            $icon = $this->props['collection']['icons'];
            $icon = str_replace(' ', '-', strtolower($icon)); // convert spaced classes to dashed

            // error_log('icon: ' . $icon);
            // error_log('stat: ');
            // error_log('$score : ' . $score);

            // $score = 4.4; // testing only 
            $limit = $this->props['collection']['limit'];

            $html = '<div class="scr-icons-row" style="">';

            for ($ii = 1; $ii <= $limit; $ii++) {
                $classes = $this->get_additional_icon_classes($score, $ii);
                $html .= '<span class="scr-icon fas fa-' . $icon . ' '  . $classes . '"></span>';
            }

            $html .= "</div>";

            return $html;
        }

        public function get_additional_icon_classes($score, $ii)
        {
            $floorScore = floor($score);
            $isLastPartialIcon = (($floorScore < $ii) && ($ii == $floorScore + 1));

            if ($ii <=  $floorScore) {
                return 'rating-100';
            } else if ($isLastPartialIcon) {
                $rating = ($score -  $floorScore) * 100;
                $rounded_rating = $this->roundUpToAny($rating, 25);
                return 'rating-' . $rounded_rating;
            }
        }

        private function roundUpToAny($number, $rounding_factor = 25)
        {
            return round($number / $rounding_factor) * $rounding_factor;
        }


        public function get_review_stat($key, $value, $score)
        {
            $html = '<li class="review-item inline field">';

            $html .= '<div class="review-item-label__text">' . $key . '</div>';

            $html .= '<div class="review-item-stars"
                title="' . $this->props['collection']['no_rated_message'] . '"
                result
            >';
            // $html .= $this->get_wrapper_html();
            // $html .= $this->get_result_html($value);
            $html .= $this->get_row_of_icons($score);

            /** Don't use the Semantic-UI RegExp identifier key that has Special Characters for validating Semantic-UI input fields
             *  The stat_identifier_key is a unique id and doesn't have a Special Characters in that key. This Key used for validating the input fields.
             */

            $stat_identifier_key = $this->get_stat_identifier_key($key);

            $html .= '<input type="hidden" id="' . $stat_identifier_key . '" name="scores[' . strtolower($key) . ']"  value="' . $value . '">';
            $html .= '</div>';

            if ($this->props['collection']['show_rating_label']) {
                $html .= '<div class="review-item-label__score">' . $score . '</div>';
            }

            $html .= '</li>';

            return $html;
        }

        protected function get_reviewed_stat($key, $value, $score)
        {
            $html = '<li class="reviewed-item">';

            $is_post_stat = isset($this->props['collection']['stat_type']) && $this->props['collection']['stat_type'] == 'post_stat' ? true : false;

            if (!$is_post_stat) {
                $html .= '<div class="reviewed-item-label__text">' . __($key, SCR_DOMAIN) . '</div>';
            }

            $html .= '<div class="reviewed-item-stars"
                title="' . $score . ' / ' . $this->props['collection']['limit'] . '"
            >';
            // $html .= $this->get_wrapper_html();
            // $html .= $this->get_result_html($value);

            $html .= $this->get_row_of_icons($score);


            $html .= '<input type="hidden" name="scores[' . strtolower($key) . ']"  value="' . $value . '">';
            $html .= '</div>';

            if ($this->props['collection']['show_rating_label']) {
                $html .= '<div class="reviewed-item-label__score">' . $score . '</div>';
            }

            $html .= '</li>';

            return $html;
        }

        protected function get_wrapper_html()
        {
            $outline_icon = $this->props['collection']['outline_icon'];
            $icon_html = "<i class='" . $outline_icon . "'></i>";
            $image_html = "<img src='" . $outline_icon . "' />";

            $outline_icon_html = ($this->props['collection']['source_type'] == 'icon') ? $icon_html : $image_html;

            $html = "<div class='stars-wrapper'>";
            for ($ii = 0; $ii < $this->props['collection']['limit']; $ii++) {
                $html .= $outline_icon_html;
            }
            $html .= "</div>";

            return $html;
        }



        protected function get_result_html($value)
        {
            $icon = $this->props['collection']['icon'];
            $filled_icon_html = "<i class='" . $icon . "'></i>";
            $filled_image_html = "<img src='" . $icon . "' />";

            $icon_html = ($this->props['collection']['source_type'] == 'icon') ? $filled_icon_html : $filled_image_html;

            $value = $this->props['collection']['animate'] == true ? 0 : $value;

            $html = '<div class="stars-result" style="width: ' . $value . '%">';
            for ($ii = 0; $ii < $this->props['collection']['limit']; $ii++) {
                $html .= $icon_html;
            }
            $html .= '</div>';

            return $html;
        }

        public function get_stat_identifier_key($key)
        {
            $global_stats = SCR_Getter::get('global_stats');

            foreach ($global_stats as $index => $stat) {
                if (strtolower($stat['stat_name']) == $key) {
                    $identifier_key = 'scr-stat-rating-' . $index;
                    return $identifier_key;
                }
            }

            return false;
        }
    }
}
