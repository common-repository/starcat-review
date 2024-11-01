<?php

namespace StarcatReview\App\Components\User_Reviews;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\User_Reviews\Controller')) {
    class Controller
    {
        public function __construct()
        {
            $this->model = new \StarcatReview\App\Components\User_Reviews\Model();
            $this->view = new \StarcatReview\App\Components\User_Reviews\View();
        }

        public function get_view(array $args, array $user_args = array())
        {
            $viewProps = $this->model->get_viewProps($args, $user_args);
            $view = $this->view->get($viewProps);

            return $view;
        }

        public function get_comment_view(int $comment_id, $type = 'review')
        {
            $comment = scr_get_comment($comment_id);
            $view = ($type == 'review') ? $this->view->get_item($comment) : $this->view->get_child_item($comment);

            $result = [
                'props' => $comment,
                'view' => $view,
            ];

            return $result;
        }

    } // END CLASS

}
