<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \StarcatReview\Includes\Settings\SCR_Getter;

if (!class_exists('CSF_Field_icon_dropdown')) {
    class CSF_Field_icon_dropdown extends \CSF_Fields

    {
        public function __construct($field, $value = '', $unique = '', $where = '', $parent = '')
        {
            parent::__construct($field, $value, $unique, $where, $parent);
        }

        public function render()
        {
            echo $this->field_before();

            echo $this->get_icon_dropdown_html();

            echo $this->field_after();
        }

        protected function get_icon_dropdown_html()
        {
            $html = '<div class="ui search selection dropdown scr-dropdown">';
            $html .= '<input type="hidden" name="' . $this->field_name() . '" value="' . $this->value . '" ' . $this->field_attributes() . '>';
            $html .= '<i class="dropdown icon"></i>';
            $html .= '<div class="default text">Select Icon</div>';

            $html .= '<div class="menu">';

            $html .= '<div class="item" data-value="star">';
            $html .= '<i class="ui star icon"></i>';
            $html .= 'Star';
            $html .= '</div>';

            $html .= '<div class="item" data-value="heart">';
            $html .= '<i class="ui heart icon" > </i>';
            $html .= 'Heart';
            $html .= '</div>';

            $html .= '<div class="item" data-value="square">';
            $html .= '<i class="ui square icon" > </i>';
            $html .= 'Square';
            $html .= '</div>';

            $html .= '<div class="item" data-value="circle">';
            $html .= '<i class="ui circle icon" > </i>';
            $html .= 'Circle';
            $html .= '</div>';

            $html .= '<div class="item" data-value="user">';
            $html .= '<i class="ui user icon" > </i>';
            $html .= 'User';
            $html .= '</div>';

            $html .= '<div class="item" data-value="bookmark">';
            $html .= '<i class="ui bookmark icon" > </i>';
            $html .= 'Bookmark';
            $html .= '</div>';

            $html .= '<div class="item" data-value="compass">';
            $html .= '<i class="ui compass icon" > </i>';
            $html .= 'Compass';
            $html .= '</div>';

            $html .= '<div class="item" data-value="trash alternate">';
            $html .= '<i class="ui trash alternate icon" > </i>';
            $html .= 'Trash Alternate';
            $html .= '</div>';

            $html .= '<div class="item" data-value="bell">';
            $html .= '<i class="ui bell icon" > </i>';
            $html .= 'Bell';
            $html .= '</div>';

            $html .= '<div class="item" data-value="lightbulb">';
            $html .= '<i class="ui lightbulb icon" > </i>';
            $html .= 'lightbulb';
            $html .= '</div>';

            $html .= '<div class="item" data-value="clock">';
            $html .= '<i class="ui clock icon" > </i>';
            $html .= 'Clock';
            $html .= '</div>';

            $html .= '<div class="item" data-value="hourglass">';
            $html .= '<i class="ui hourglass icon" > </i>';
            $html .= 'Hourglass';
            $html .= '</div>';

            $html .= '<div class="item" data-value="gem">';
            $html .= '<i class="ui gem icon" > </i>';
            $html .= 'Gem';
            $html .= '</div>';

            $html .= '<div class="item" data-value="map">';
            $html .= '<i class="ui map icon" > </i>';
            $html .= 'Map';
            $html .= '</div>';

            $html .= '<div class="item" data-value="sticky note">';
            $html .= '<i class="ui sticky note icon" > </i>';
            $html .= 'Sticky note';
            $html .= '</div>';

            $html .= '<div class="item" data-value="sun">';
            $html .= '<i class="ui sun icon" > </i>';
            $html .= 'Sun';
            $html .= '</div>';

            $html .= '<div class="item" data-value="lemon">';
            $html .= '<i class="ui lemon icon" > </i>';
            $html .= 'Lemon';
            $html .= '</div>';

            $html .= '<div class="item" data-value="comment">';
            $html .= '<i class="ui comment icon" > </i>';
            $html .= 'Comment';
            $html .= '</div>';

            $html .= '<div class="item" data-value="comments">';
            $html .= '<i class="ui comments icon" > </i>';
            $html .= 'Comments';
            $html .= '</div>';

            $html .= '<div class="item" data-value="file">';
            $html .= '<i class="ui file icon" > </i>';
            $html .= 'File';
            $html .= '</div>';

            $html .= '<div class="item" data-value="folder">';
            $html .= '<i class="ui folder icon" > </i>';
            $html .= 'Folder';
            $html .= '</div>';

            $html .= '<div class="item" data-value="envelope">';
            $html .= '<i class="ui envelope icon" > </i>';
            $html .= 'Envelope';
            $html .= '</div>';

            $html .= '<div class="item" data-value="hdd">';
            $html .= '<i class="ui hdd icon" > </i>';
            $html .= 'Hdd';
            $html .= '</div>';

            $html .= '<div class="item" data-value="paper plane">';
            $html .= '<i class="ui paper plane icon" > </i>';
            $html .= 'Paper Plane';
            $html .= '</div>';

            $html .= '<div class="item" data-value="handshake">';
            $html .= '<i class="ui handshake icon" > </i>';
            $html .= 'Handshake';
            $html .= '</div>';

            $html .= '<div class="item" data-value="thumbs up">';
            $html .= '<i class="ui thumbs up icon" > </i>';
            $html .= 'Thumbs Up';
            $html .= '</div>';

            $html .= '<div class="item" data-value="thumbs down">';
            $html .= '<i class="ui thumbs down icon" > </i>';
            $html .= 'Thumbs Down';
            $html .= '</div>';

            $html .= '<div class="item" data-value="meh">';
            $html .= '<i class="ui meh icon" > </i>';
            $html .= 'Meh';
            $html .= '</div>';

            $html .= '<div class="item" data-value="smile">';
            $html .= '<i class="ui smile icon" > </i>';
            $html .= 'Smile';
            $html .= '</div>';

            $html .= '<div class="item" data-value="frown">';
            $html .= '<i class="ui frown icon" > </i>';
            $html .= 'Frown';
            $html .= '</div>';

            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }
    }
}

if (!function_exists('csf_validate_stat_limit')) {

    function csf_validate_stat_limit($value)
    {
        if (!is_numeric($value)) {
            return esc_html__('Please giva a numeric limit !', 'csf');
        }

        if ($value > 100 || $value < 1) {
            return esc_html__('Please giva a value between one to hundread ( 1 to 100 ) limit !', 'csf');
        }

        if (!ctype_digit($value)) {
            return esc_html__('Please giva a rounded value limit !', 'csf');
        }
    }
}

if (!function_exists('csf_validate_recaptcha_site_key')) {

    function csf_validate_recaptcha_site_key($value)
    {
        if (SCR_Getter::get('ur_show_captcha') == true && !sanitize_key($value)) {
            return esc_html__('Please add a reCAPTCHA Site Key !', SCR_DOMAIN);
        }
    }
}

if (!function_exists('csf_validate_recaptcha_secret_key')) {

    function csf_validate_recaptcha_secret_key($value)
    {
        if (SCR_Getter::get('ur_show_captcha') == true && !sanitize_key($value)) {
            return esc_html__('Please add a reCAPTCHA Secret Key !', SCR_DOMAIN);
        }
    }
}

if (!function_exists('scr_csf_premium_callback_function')) {
    function scr_csf_premium_callback_function($args = [])
    {
        $addon_link = $args['link'];
        $addon_name = $args['name'];

        $bundle_link = 'https://starcatwp.com/freemius_product/starcat-reviews-business-bundle/';
        $bundle_name = 'Go Pro Addons Bundle';

        $html = '<div class="scr-csf-premiumtease-container">';
        $html .= '<a class="ui primary labeled icon small button" href = "' . $addon_link . '"><i class="sign in alternate large icon"></i> ';
        $html .= sprintf(__('Go Pro %s Addon', SCR_DOMAIN), $addon_name);
        $html .= '</a>';

        $html .= '<a class="ui green labeled icon small button" href = "' . $bundle_link . '" target="_blank"><i class="boxes large icon"></i> ';
        $html .= $bundle_name;
        $html .= '</a>';

        $html .= '</div>';

        echo $html;
    }
}
