<?php

namespace StarcatReview\Services;

use StarcatReview\Includes\Settings\SCR_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\Services\Recaptcha')) {
    class Recaptcha
    {

        public static function load_script()
        {
            // <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            // wp_enqueue_script('semantic-js', SCR_URL . 'includes/assets/vendors/semantic/bundle/semantic.min.js', array('jquery'));
        }

        public static function load_v2_html()
        {
            $site_key = SCR_Getter::get_recaptcha_site_key();

            $html = '';
            $html .= '<div class="field">';
            $html .= self::js_script();
            $html .= '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
            $html .= '<div class="g-recaptcha" data-callback="starcat_recaptcha_callback" data-sitekey="' . $site_key . '"></div>';
            $html .= '<input type="hidden" id="src_recaptcha" name="src_recaptcha" value="">';
            $html .= '</div>';
            return $html;
        }

        public static function js_script()
        {
            $html = '';
            $html .= '<script>function starcat_recaptcha_callback(response) { ';
            $html .= 'console.log("response:"); console.log(response);';
            $html .= 'jQuery("form.scr-user-review #src_recaptcha").val(response);';
            $html .= '}</script>';

            return $html;
        }

        public static function verify()
        {
            $post_id  = isset($_POST['post_id']) && !empty($_POST['post_id']) ? $_POST['post_id'] : 0;
            $post = get_post($post_id);
            $secret_key = SCR_Getter::get('recaptcha_secret_key');
            if(isset($post) && $post->post_type == 'product'){
                $secret_key = SCR_Getter::get('woo_recaptcha_secret_key');
            }
            // don't sanitize the captcha response data.
            $captcha_response = isset($_POST["src_recaptcha"]) &&  !empty($_POST["src_recaptcha"]) ? $_POST["src_recaptcha"] : '';
            if(empty($captcha_response)){
                return false;
            } 
            
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => $secret_key,
                'response' => $captcha_response,
            );

            $query = http_build_query($data);
            $options = array(
                'http' => array(
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($query) . "\r\n" .
                    "User-Agent:MyAgent/1.0\r\n",
                    'method' => 'POST',
                    'content' => $query,
                ),
            );

            $context = stream_context_create($options);
            $verify = file_get_contents($url, false, $context);
            $captcha_success = json_decode($verify);
            if ($captcha_success->success == false) {
                // echo "<p>You are a bot! Go away!</p>";
                $captcha_success = false;
            } else if ($captcha_success->success == true) {
                $captcha_success = true;
                // echo "<p>You are not not a bot!</p>";
            }

            return $captcha_success;
        }
    }
}