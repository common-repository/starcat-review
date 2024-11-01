<?php

namespace StarcatReview\App\Components\User_Reviews;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\User_Reviews\View')) {
    class View
    {
        public function get(array $viewProps)
        {
            $html = '';

            $this->itemProps = $viewProps['items'];
            $this->collection = $viewProps['collection'];
            $this->capability = $viewProps['collection']['capability'];

            $show_list_title = $this->collection['show_list_title'] ? true : false;

            if ($show_list_title == true) {
                $html .= '<h3 class="ui dividing header"> ' . $this->collection['list_title'] . '</h3>';
            }
            $post_id = $this->collection['post_id'];
            $html .= '<div class="ui scr_user_reviews list comments" data-post-id="' . $post_id . '">';
            if (isset($viewProps['items']) && !empty($viewProps['items'])) {
                foreach ($viewProps['items'] as $comment) {
                    if ($this->can_view_comment($comment) && $comment['parent'] == 0) {
                        $html .= $this->get_item($comment);
                    }
                }
            }

            $html .= $this->get_reply_form();

            $html .= '</div>';

            $html .= $this->get_pagination_html($viewProps['items']);

            return $html;
        }

        protected function can_view_comment($comment)
        {
            $can = false;

            if ($comment['approved'] == 1 || $comment['user_id'] == $this->collection['current_user_id']) {
                $can = true;
            }

            // Used by non-logged-in-user
            $can = apply_filters('scr_can_view_comment', $can, $comment);

            return $can;
        }

        public function get_item($comment)
        {
            // error_log('comment : ' . print_r($comment, true));

            $data_props = htmlspecialchars(json_encode($comment['props']), ENT_QUOTES, 'UTF-8');

            $html = '<div class="comment" id="' . $comment['ID'] . '" data-props="' . $data_props . '">';
            $html .= $this->get_avatar($comment);

            $html .= '<div class="content">';
            $html .= $this->get_content_meta($comment);
            $html .= $this->get_content_text($comment);
            $html .= $this->get_content_moderation_info($comment);
            $html .= $this->get_content_actions($comment);

            // 1 level indentation of comment childrens
            if ($comment['parent'] == 0 && isset($comment['childrens']) && !empty($comment['childrens'])) {
                foreach ($comment['childrens'] as $child_comment) {
                    $html .= $this->get_child_item($child_comment);
                }
            }
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }

        public function get_child_item($comment)
        {
            $can_edit = isset($comment['can_edit']) ? $comment['can_edit'] : false;

            $html = '<div class="comment" id="' . $comment['ID'] . '" data-comment-parent-id ="' . $comment['parent'] . '" >';
            $html .= $this->get_avatar($comment);

            $html .= '<div class="content">';
            $html .= $this->get_content_meta($comment);
            $html .= '<div class="text">' . $comment['content'] . '</div>';
            $html .= $this->get_content_moderation_info($comment, 'Reply');

            $html .= '<div class="actions">';
            $html .= '<div class="links">';
            if ($can_edit) {
                $html .= '<a class="reply_edit_link"><i class="edit icon"></i> ' . __('EDIT', SCR_DOMAIN) . '</a>';
            }
            $html .= '</div></div>';
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }

        protected function get_pagination_html($items)
        {
            $html = '';

            $display = (!empty($items) && count($items) > 9) ? '' : 'style="display: none;"';

            $html .= '<ul class="ui pagination scr-pagination menu" ' . $display . '>';
            for ($ii = 1; $ii <= 2; $ii++) {
                # code...
                $html .= '<li class="active"><a class="page" href="">' . $ii . '</a></li>';
            }
            $html .= '</ul>';

            return $html;
        }

        protected function get_avatar($comment)
        {
            return '<a class="avatar"> ' . $comment['avatar'] . '</a>';
        }

        protected function get_content_meta($comment)
        {
            $html = '';
            $html .= '<span class="author"> ' . $comment['author'] . ' </span>';

            if ($comment['parent'] == 0 && $comment['is_verified_review']) {
                $html .= '<em class="verified">(' . __('verified owner', SCR_DOMAIN) . ')</em> ';
                $html .= '<i class="check circle blue icon"></i>';
            }

            $html .= '<div class="metadata">';
            $html .= '<span class="date">' . $comment['date'] . '</span>';
            $html .= '<span class="time">' . __('AT', SCR_DOMAIN) . ' ' . $comment['time'] . '</span>';

            if ($comment['parent'] == 0) {
                $html .= '<span class="postDate" data-postDate="' . $comment['time_stamp'] . '"></span>'; // used by list-control.JS
                $html .= '<span class="likes" data-likes="' . $comment['likes'] . '"></span>'; // used by list-control.JS
                $html .= '<span class="positiveScore" data-positiveScore="' . $comment['rating'] . '"></span>'; // used by list-control.JS
            }

            $html .= '</div>';

            return $html;

        }

        protected function get_content_text($comment)
        {
            $title = '';
            $stats_view = '';
            $prosandcons_view = '';
            if (isset($comment['stats'])) {
                $stats = new \StarcatReview\App\Components\Stats\Controller($comment['stats']);
                $stats_view = $stats->get_view();
            }

            if (isset($comment['prosandcons'])) {
                $prosandcons = new \StarcatReview\App\Components\ProsAndCons\Controller();
                $prosandcons_view = $prosandcons->get_view($comment['prosandcons']);
            }

            if (isset($comment['title'])) {
                $title = '<div class="title review-card__header"> ' . $comment['title'] . ' </div>';
            }

            $html = '<div class="text">';
            $html .= $title;
            $html .= '<div class="stats"> ' . $stats_view . '</div>';
            $html .= '<div class="description review-card__body"><p>' . $comment['content'] . '</p></div>';
            $html .= $prosandcons_view;
            $html .= $this->get_attachments($comment);

            $html .= '</div>';

            return $html;
        }

        public function get_attachments($comment)
        {
            $html = '';
            if (isset($comment['attachments']) && !empty($comment['attachments'])) {
                $review_photos = apply_filters('scr_photo_reviews/get_single_review_photos', $comment['attachments']);
                $html .= is_string($review_photos) ? $review_photos : '';
            }

            return $html;
        }

        protected function get_content_moderation_info($comment, $title = 'Review')
        {
            $html = '';
            if ($comment['approved'] == 0 && $comment['user_id'] == $this->collection['current_user_id']) {
                $html .= '<div class="comment_in_moderation">' . sprintf(__('%s in Moderation !', SCR_DOMAIN), __($title, SCR_DOMAIN)) . '</div>';
            }

            return $html;
        }

        protected function get_content_actions($comment)
        {
            $html = '';
            $html .= '<div class="actions">';
            $html .= $this->get_action_links($comment);
            $html .= $this->get_action_helpful($comment);
            $html .= '</div>';

            return $html;
        }

        private function get_action_links($comment)
        {
            $html = '<div class="links">';

            $can_reply = $this->capability['can_user_reply'];
            $can_edit_comment = isset($comment['can_edit']) && !empty($comment['can_edit']) ? $comment['can_edit'] : '';

            if ($can_reply) {
                $html .= '<a class="reply_link"><i class="reply icon"></i> ' . __('REPLY', SCR_DOMAIN) . '</a>';
            }
            if ($can_edit_comment) {
                $html .= '<a class="edit_link"><i class="edit icon"></i> ' . __('EDIT', SCR_DOMAIN) . '</a>';
            }
            // if ($this->collection['can_delete']) {
            //     $html .= '<a class="delete_link"><i class="delete icon"></i> DELETE</a>';
            // }

            $html .= '</div>';
            return $html;
        }

        private function get_action_helpful($comment)
        {
            $vote = isset($comment['votes']) && !empty($comment['votes']) ? $comment['votes'] : [];

            $like_active = (isset($vote['active']) && $vote['active'] === 'like') ? 'active' : '';
            $dislike_active = (isset($vote['active']) && $vote['active'] === 'dislike') ? 'active' : '';

            $likes = (isset($vote['likes']) && !empty($vote['likes']) && $vote['likes'] > 0) ? $vote['likes'] : 0;
            $dislikes = (isset($vote['dislikes']) && !empty($vote['dislikes']) && $vote['dislikes'] > 0) ? $vote['dislikes'] : 0;

            $html = '<div class="helpful"> ';
            if ($this->capability['can_user_vote'] && $this->collection['enable_voting']) {
                $html .= '<div class="vote likes-and-dislikes" data-comment-id="' . $comment['ID'] . '">';
                $html .= __('Was this helpful to you ?', SCR_DOMAIN) . ' ';
                $html .= '<a class="like ' . $like_active . '"><i class="bordered thumbs up outline icon"></i><span class="likes">' . $likes . '</span></a>';
                $html .= '<a class="dislike ' . $dislike_active . '"><i class="bordered thumbs down outline icon"></i><span class="dislikes">' . $dislikes . '</span></a>';
                $html .= '</div>';
            }
            if (($likes || $dislikes) != 0) {
                $html .= '<div class="vote-summary">';
                $html .= '<span class="helpful">' . sprintf(__('%d of %d people found this review helpful', SCR_DOMAIN), $likes, $vote['people']) . '</span>';
                // $html .= '<span class="helpful">' . $likes . ' of ' . $vote['people'] . ' </span> people found this review helpful </span>';
                $html .= '</div>';
            }

            $html .= '</div>';

            return $html;
        }

        private function get_reply_form()
        {
            $html = '<form class="ui user-review-reply form" action="scr_user_review_submission" method="post" data-post-id ="' . $this->collection['post_id'] . '">';
            $html .= '<div class="field">';
            $html .= '<textarea rows="2" name="description" placeholder="Reply to @them ..." ></textarea>';
            $html .= '</div>';
            $html .= '<div class="ui mini blue submit button">' . __('REPLY', SCR_DOMAIN) . '</div>';
            $html .= '<div class="ui mini cancel button">' . __('Cancel', SCR_DOMAIN) . '</div>';
            $html .= '</form>';

            return $html;
        }
    } // END CLASS
}
