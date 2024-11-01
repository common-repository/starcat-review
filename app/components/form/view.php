<?php

namespace StarcatReview\App\Components\Form;

use \StarcatReview\Includes\Settings\SCR_Getter;
use \StarcatReview\Services\Recaptcha as Recaptcha;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Form\View')) {
    class View
    {
        public function __construct(array $viewProps)
        {
            $this->props = $viewProps;
            $this->capability = $viewProps['collection']['capability'];
        }

        public function get()
        {
            $class = '';
            $title = '';
            $review = [];
            $display = '';
            $cancel_btn = '';
            $description = '';
            $method_type = 'POST';
            $submit_btn_name = __('Submit', SCR_DOMAIN);
            $form_title = '<h2 class="ui header">' . __($this->props['collection']['form_title'], SCR_DOMAIN) . '</h2>';

            $show_form = $this->can_show_review_form();
            if (!$show_form) {
                $display = 'style="display: none"';
            }
            $post_id = $this->props['collection']['post_id'];

            $html = '<form
            class="ui form scr-user-review ' . $class . '"
            action="scr_user_review_submission"
            method="post"
            enctype="multipart/form-data"
            post_id ="' . $post_id . '"
            ' . $display . '
            data-method="' . $method_type . '"
            data-post-id="' . $post_id . '"
            >';

            if ($this->props['collection']['show_form_title']) {
                $html .= $form_title;
            }

            $html = apply_filters('scr_user_form_start', $html, $review);

            if ($this->props['collection']['show_title']) {
                $html .= '<div class="inline field">';
                // $html .= '<label>Review Title</label>';
                $html .= '<input type="text" name="title" placeholder="' . __('Title', SCR_DOMAIN) . '" value="' . $title . '"/>';
                $html .= '</div>';
            }

            if ($this->props['collection']['show_stats']) {
                $html .= '<div class="rating fields">';
                $html .= $this->get_user_review_stats();
                $html .= '</div>';
            }

            if ($this->props['collection']['show_description']) {
                $html .= '<div class="field">';
                // $html .= '<label>Review Description</label>';
                $html .= '<textarea rows="5" spellcheck="false" name="description" placeholder="' . __('Description', SCR_DOMAIN) . '">' . $description . '</textarea>';
                $html .= '</div>';
            }

            if ($this->props['collection']['show_prosandcons']) {
                $html .= $this->get_pros_and_cons();
            }

            if ($this->props['collection']['enable_photo_reviews']) {
                $upload_photos_field = apply_filters('scr_photo_reviews/get_single_review_upload_photos_field', $review);
                $upload_photos_field_html = is_string($upload_photos_field) ? $upload_photos_field : '';
                $html .= $upload_photos_field_html;
            }

            if ($this->props['collection']['show_captcha']) {
                $html .= Recaptcha::load_v2_html();
            }

            $user_form_end = apply_filters('scr_user_form_end', '', $review);
            $html .= $user_form_end;

            $html .= '<div class="field">';
            $html .= '<div class="ui blue submit ' . $class . ' button"> ' . $submit_btn_name . ' </div>';
            $html .= $cancel_btn;
            $html .= '</div>';

            $html .= '</form>';

            return $html;
        }

        /*
         * TODO: Append our fields to Themes Comment_form
         * Not Used
         */
        public function get_fields()
        {
            $html = '';
            if ($this->props['collection']['show_title']) {
                $html .= '<div class="inline field">';
                $html .= '<input type="text" name="title" placeholder="Your title" value="" required/>';
                $html .= '</div>';
            }

            if ($this->props['collection']['show_stats']) {
                $html .= '<div class="rating fields">';
                $html .= $this->get_user_review_stats();
                $html .= '</div>';
            }

            if ($this->props['collection']['show_description']) {
                $html .= '<div class="field">';
                $html .= '<textarea rows="4" spellcheck="true" name="comment" placeholder="Your review" required></textarea>';
                $html .= '</div>';
            }

            if ($this->props['collection']['show_prosandcons']) {
                $html .= $this->get_pros_and_cons();
            }

            if ($this->props['collection']['show_captcha']) {
                $html .= Recaptcha::load_v2_html();
            }

            return $html;
        }

        protected function get_pros_and_cons()
        {
            $prosandcons = new \StarcatReview\App\Components\ProsAndCons\Controller();
            return $prosandcons->get_fields($this->props);
        }

        protected function get_user_review_stats()
        {
            $woo_stats_class = SCR_Getter::is_single_product_post() || SCR_Getter::is_admin_product_page() ? 'woo-stats' : '';

            $html = '';
            $html .= '<ul class="review-list ' . $woo_stats_class . '"
                data-type="' . $this->props['collection']['stats_args']['type'] . '"
                data-limit="' . $this->props['collection']['stats_args']['limit'] . '"
                data-steps="' . $this->props['collection']['stats_args']['steps'] . '"
                data-no-rated-message ="' . $this->props['collection']['stats_args']['no_rated_message'] . '"
                data-list="items"
                >';

            $global_stats = $this->props['collection']['stats_args']['global_stats'];
            if ($this->props['collection']['stats_args']['singularity'] == 'single') {
                $global_stats = [$global_stats[0]];
            }

            if (isset($global_stats) && !empty($global_stats)) {
                foreach ($global_stats as $stat) {
                    switch ($this->props['collection']['stats_args']['type']) {
                        case "star":
                            $html .= $this->get_star_rating($stat);
                            break;
                        // case "bar":
                        //     $html .= $this->get_bar_rating($key);
                        //     break;
                        default:
                            $html .= $this->get_star_rating($stat);
                    }
                }
            }
            $html .= '</ul>';

            return $html;
        }

        protected function get_star_rating($stat)
        {
            $score = 0;
            $rating = 0;

            $rating_args['collection'] = $this->props['collection']['stats_args'];
            $star_rating = new \StarcatReview\App\Views\Rating_Types\Star_Rating($rating_args);

            $stat_name = strtolower($stat['stat_name']);
            $list_item_html = $star_rating->get_review_stat($stat_name, $rating, $score);

            return $list_item_html;
        }

        protected function get_bar_rating($key)
        {
            return $this->bar_rating->get_review_stat($key, 5, 5);
        }

        protected function can_show_review_form()
        {
            // User Already Reviewed or Not Logged in User
            $user_can_see_the_form = $this->capability['can_user_review'] == 1 ? true : false;

            /**
             *  NOTE : Use of Review Form
             *  For users want to edit their own reviews, the review form should be available on the page.
             */
            $show_review_form = isset($this->props['collection']['show_review_form']) && $this->props['collection']['show_review_form'] == 1 ? 1 : 0;
            $show_form = ($user_can_see_the_form && $show_review_form == 1) ? true : false;
            return $show_form;
        }

        //  Todo: Text Rating
        // protected function get_text_rating_fallback()
        // {
        //     return '<div class="column">
        //         <div> Feature </div>
        //         <div class="ui left labeled input">
        //             <div class="ui basic label"> # </div>
        //             <input type="number" name="review_number" placeholder="Number" min="1" max="100" maxlength="2">
        //         </div>
        //     </div>';
        // }

        //  Todo: Range Rating
        // protected function get_range_rating_fallback($value = 10, $min = 0, $max = 100)
        // {
        //     $html = '<div class="scr-rating-wrapper"><hr class="scr-divider">';

        //     $html .= '<div class="scr-user-review__rating">';
        //     $html .= '<input type="range" min="' . $min . '" max="' . $max . '" value="' . $value . '" class="scr-user-review__range">';
        //     $html .= '</div><span class="scr-user-review__value">' . $value . " / " . $max . "%" . '</span>';
        //     $html .= '</div>';

        //     return $html;
        // }
    } // END CLASS
}