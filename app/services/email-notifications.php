<?php

namespace StarcatReview\App\Services;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Services\Email_Notifications')) {

    class Email_Notifications{

        public function default_args(){
            $mail_args = array();
            $mail_args['from_address'] = "";
            $mail_args['mailer'] = "";
            $mail_args['return_path'] = "";
            $mail_args['to_address'] = array();
            $mail_args['subject'] = "";
            $mail_args['content'] = "";
            $mail_args['attachments'] = array();
            $mail_args['cc']    = array();
        }
        
        public function send_mail($args){

            $user_mail_address   = $args['user_mail_address']; // its comes array || string
            $user_name      = $args['user_name'] != ""?$args['user_name']:"Starcat Review";
            $to_address     = $args['to_address'] != "" ?$args['to_address'] :""; // its comes array || string
            $subject        = $args['subject'];
            $mail_content   = $args['content'];
            $disclaimer     = isset($args['disclaimer']) ?$args['disclaimer']:"";
            $attachments    = isset($args['attachments']) ? $args['attachments'] : array();
            
            $headers        = array();
            $headers[]      = 'Content-Type: text/html; charset=UTF-8'."\r\n";         
            $headers[]      = 'From: '.$user_name.' &#60;'.$user_mail_address.'&#62;'."\r\n";
            
            // $header_text = '';
            // foreach($headers as $header_content){
            //     $header_text .= $header_content;
            // }
            // echo $header_text;
            
          //  wp_mail($to_address,$subject,$mail_content,$headers);
        }

        
    }
    
}
?>
