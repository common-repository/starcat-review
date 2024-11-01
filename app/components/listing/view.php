<?php


namespace StarcatReview\App\Components\Listing;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Listing\View')) {
    class View
    {
        private $html;

        public function __construct()
        {
            /* Views */
            $this->card = new \StarcatReview\App\Views\Blocks\Card();
            $this->controls_builder = new \StarcatReview\App\Builders\Controls_Builder();
        }

        public function get_html($viewProps)
        {
            $collectionProps = $viewProps['collection'];

            $html = '<div id="scr-controlled-list" data-collectionProps="' . $this->get_js_config($collectionProps) . '">';
            $html .= '<h2>' . $collectionProps['title'] . '</h2>';

            if ($collectionProps['show_controls']) {
                $html .= $this->controls_builder->get_controls($collectionProps['show_controls']);
            }

            $html .= $this->get_card_collection($viewProps);

            if ($collectionProps['pagination']) {
                $html .= $this->get_pagination_html($viewProps);
            }

            $html .= '</div>';

            return $html;
        }

        /* PRIVATE CLASS */

        private function get_js_config($collectionProps)
        {
            $js_config = [];
            $js_config['pagination'] = $collectionProps['pagination'];
            $js_config['page'] = $collectionProps['posts_per_page'];

            $js_config = json_encode($js_config);

            $js_config = str_replace('"', "<", $js_config);

            return $js_config;
        }
        private function get_pagination_html($viewProps)
        {
            $html = '';
            $html .= '<ul class="ui pagination scr-pagination menu">';

            for ($ii = 1; $ii <= $viewProps['collection']['total_pages']; $ii++) {
                # code...
                $html .= '<li class="active"><a class="page" href="">' . $ii . '</a></li>';
            }

            $html .= '</ul>';
            return $html;
        }

        private function get_card_collection($viewProps)
        {
            $itemsProps = $viewProps['items'];

            $html = '';
            $html .= '<div id="scr-cat-collection" class="scr-collection list row">';

            if (isset($itemsProps) && is_array($itemsProps) && sizeof($itemsProps) > 0) {
                foreach ($itemsProps as $key => $item) {
                    // Set initial $ii
                    if (!isset($ii)) $ii = 0;

                    // Assign card to html
                    $html .= $this->get_single_card($item, $ii);
                    // increment $ii
                    $ii++;
                }
            } else {
                error_log('$terms is not set (or) not array (or) empty.');
            }

            $html .= '</div>';

            return $html;
        }

        private function get_single_card($item, $ii)
        {
            return $this->card->get_view($item);
        }

        private function get_excerpt($content)
        {
            $word_count = 150;
            $excerpt = $content;
            $excerpt = strip_tags($excerpt);
            $excerpt = substr($excerpt, 0, $word_count);
            $excerpt .= ' ...';

            return $excerpt;
        }
    } // END CLASS
}