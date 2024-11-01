<?php

namespace StarcatReview\App\Views\Blocks;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Blocks\Simple_Collection')) {
    class Simple_Collection
    {

        public function __construct()
        {}

        /* TODO: Write validation for inputs in Blocks.php */
        public function get_view($itemsProps, $collectionProps)
        {
            $html = '';
            $html .= '<div class="scr-collection row">';

            $columns = $collectionProps['no_of_cols'];
            $count = 1;

            foreach ($itemsProps as $key => $item) {

                // start row
                // if ($count % $columns == 1) $html .= '<div class="row">';

                $html .= $this->get_single_card($item);

                // close row
                // if ($count  % $columns == 0) $html .= '</div>';

                $count++;
            }

            $html .= '</div>';

            return $html;
        }

        /* HTML for Single Card */
        private function get_single_card($item)
        {
            $html = '';

            $html .= '<div class="scr-collection__col col-xs-12 col-lg-4">'; // can't add additional classes
            $html .= '<div class="scr-review-card">';
            $html .= '<div class="review-card__header">' . __($item['title'], SCR_DOMAIN) . '</div>';
            $html .= '<div class="review-card__body">' . __($item['content'], SCR_DOMAIN) . '</div>';
            $html .= '<div class="review-card__footer"><a href="' . $item['url'] . '">' . __('See all', SCR_DOMAIN) . ' >> </a></div>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }
    } // END CLASS
}
