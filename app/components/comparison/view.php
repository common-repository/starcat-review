<?php


namespace StarcatReview\App\Components\Comparison;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Comparison\View')) {
    class View
    {
        private $html;

        public function __construct()
        {
            /* Views */
            // $this->Comparison_Table = new \StarcatReview\App\Views\Blocks\Comparison_Table();
        }

        public function get_html($args = [])
        {
            $html = '';
            $html .= '<section class="cd-products-comparison-table">';
            // $html .= $this->get_header();
            $html .= '<div class="cd-products-table">';
            $html .= $this->features($args['cols']);
            $html .= $this->get_columns($args, $args['cols']);
            $html .= $this->navigation();
            $html .= '</div> <!-- .cd-products-table -->';
            $html .= '</section> <!-- .cd-products-comparison-table -->';
            return $html;
        }

        public function get_header()
        {


            $html = '';
            $html .= '<header class="col-12">';
            $html .= '<div class="col-lg-6"><div class="col-xs-12">';
            $html .= '<div class="ui input focus scr-search-container" style="width:100%;">';
            $html .= '<input type="text" class="scr-search" placeholder="Search...">';
            $html .= '<div class="scr-search-lists"></div></div>';
            $html .= '</div></div>';
            $html .= '<div class="col-xs-6 actions">';
            $html .= '<a href="#0" class="reset">Reset</a>';
            $html .= '<a href="#0" class="filter">Filter</a>';
            $html .= '</div>';
            $html .= '</header>';
            return $html;
        }

        public function features($args)
        {
            $html = '';
            $html .= '<div class="features">';
            $html .= '<div class="top-info">Features</div>';
            $html .= '<ul class="cd-features-list" id="scr-stats-list">';

            // error_log('$stat_cols : ' . print_r($stat_cols, true));
            for ($ii = 0; $ii < sizeof($args); $ii++) {
                $html .= '<li>' . $args[$ii] . '</li>';
            }

            $html .= '</ul>';
            $html .= ' </div> <!-- .features -->';

            return $html;
        }

        public function get_columns($args, $stat_cols)
        {

            $html = '';
            $html .= '<div class="cd-products-wrapper">';
            $html .= '<ul class="cd-products-columns" style="display:flex;">';

            foreach ($args['posts'] as $key => $single_product_stats) {
                // error_log("single_product_stats" . print_r($single_product_stats, true));
                $html .= $this->single_product($single_product_stats, $stat_cols);
            }


            if ($args['show_type'] == "dynamic") {
                if (count($args) > 0) {
                    $html .= $this->search_filter_product();
                } else if (count($args) == 0) {
                    $html .= $this->search_filter_product();
                }
            }

            $html .= '</ul> <!-- .cd-products-columns -->';
            $html .= '</div> <!-- .cd-products-wrapper -->';


            return $html;
        }

        public function single_product($args, $stat_cols)
        {
            $html = '';

            $html .= '<li class="product">';
            $html .= '<div class="top-info">';
            $html .= '<div class="close-product">';
            $html .= '<i class="window close outline icon" style="font-size:25px;"></i>';
            $html .= '</div>';
            // $html .= '<div class="check"></div>';
            $html .= '<img class="featured-image" src="' . $args['featured_image_url'] . '" alt="product image">';
            $html .= '<h3>' . $args['title'] . '</h3>';
            $html .= '</div> <!-- .top-info -->';

            $html .= $this->single_product_features($args, $stat_cols);
            $html .= '</li> <!-- .product -->';
            //$html .= '<li class="product">';
            //$html .= '</li> ';

            return $html;
        }

        public function single_product_features($args, $stat_cols)
        {
            $get_stats = $args['stats'];

            $get_overall_stats  = $args['overall_ratings'];
            $html = '';

            $html .= '<ul class="cd-features-list">';

            for ($ii = 0; $ii < sizeof($stat_cols); $ii++) {
                $stat_name = $stat_cols[$ii];
                if ($stat_name == "scr-ratings") {
                    $stat_value = $get_overall_stats['dom'];
                    $html .= '<li class="scr-ct-ratings" data-stat="' . $stat_name . '">' . $stat_value . '</li>';
                } else {
                    $stat_value = isset($get_stats[$stat_name]) ? $get_stats[$stat_name]['rating'] : 'X';
                    $html .= '<li data-stat="' . $stat_name . '">' . $stat_value . '</li>';
                }
            }
            // foreach ($stats as $key => $stat) {
            //     $html .= '<li>' . $stat . '</li>';
            // }


            $html .= '</ul>';

            return $html;
        }

        public function navigation()
        {
            $html = '';

            $html .= '<ul class="cd-table-navigation">';
            $html .= '<li><a href="#0" class="prev inactive">Prev</a></li>';
            $html .= '<li><a href="#0" class="next">Next</a></li>';
            $html .= '</ul>';

            return $html;
        }

        public function search_filter_product()
        {

            $html = '';
            $html .= '<li class="product scr-search-filter-wrapper">';
            $html .= '<div class="top-info">';
            //$html .= '<div class="check"></div>';
            //$html .= '<img class="featured-image" src="" alt="product image">';
            $html .= '<h4>Add Product</h4>';
            $html .= '<div class="ui search scr-product-search">';
            $html .= '<div class="ui input">';
            $html .= '<input type="text" class="prompt scr-search" placeholder="Search" />';
            $html .= '</div>';
            $html .= '<div class="results"></div>';
            $html .= '</div>';
            //$html .= $this->add_product_btn();
            $html .= '</div> <!-- .top-info -->';
            $html .= '<ul class="cd-features-list"></ul>';
            $html .= '</li> <!-- .product -->';

            return $html;
        }

        public function add_product_btn()
        {
            $html = '';
            $html .= '<div class="ui single column grid scr-compare-add-button" style="padding:5px;">';
            $html .= '<div class="row"><div class="column">';
            $html .= '<button class="ui button scr-add-product" style="width:40%;">Add</button>';
            $html .= '</div></div>';
            $html .= '</div>';
            return $html;
        }


        /* PRIVATE CLASS */
    } // END CLASS
}
