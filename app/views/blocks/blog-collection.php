<?php

namespace StarcatReview\App\Views\Blocks;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Views\Blocks\Blog_Collection')) {
    class Blog_Collection
    {

        public function __construct()
        { }

        /* TODO: Write validation for inputs in Blocks.php */
        public function get_view($itemsProps, $collectionProps)
        {
            $html = '';
            $html .= '<div class="scr-categories-list scr-container container">';

            $columns = $collectionProps['no_of_cols'];
            $count = 1;

            foreach ($itemsProps as $key => $item) {

                // start row
                if ($count % $columns == 1) $html .= '<div class="row">';

                $html .= $this->get_single_card($item);

                // close row 
                if ($count  % $columns == 0) $html .= '</div>';

                $count++;
            }

            $html .= '</div>';

            return $html;
        }

        /* HTML for Single Card */
        private function get_single_card($item)
        {
            $html = '';

            $html .= '<div class="ast-row">';
            $html .= '<article itemtype="https://schema.org/CreativeWork"
                     itemscope="itemscope"
                     id="post-1"
                     class="post-1 post scr-archive-post">';
            $html .= '<h2 class="scr-entry-title">' . $item['title'] . '</h2>';
            $html .= $item['content'];
            $html .= '</article>';
            $html .= '</div>';

            return $html;
        }
    } // END CLASS
}