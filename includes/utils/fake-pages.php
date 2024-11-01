<?php

class SCRFakePages
{
    public function __construct()
    {
        // require_once SCR_PATH . 'includes/lib/photo-reviews-addon/starcat-review-photo-reviews.php';

        add_filter('the_posts', array($this, 'fake_pages'));
    }

    public function get_page_content()
    {
        $content = '<b>List of Review UI Components for Testing Purpose Only</b></br></br>';
        // Other components

        $all_photos = apply_filters('scr_photo_reviews/get_all_photos', array());
        $all_photos_content = is_string($all_photos) ? $all_photos : '';
        $review_photos = apply_filters('scr_photo_reviews/get_single_review_photos', array());
        $review_photos_content = is_string($review_photos) ? $review_photos : '';

        $content .= $all_photos_content;
        $content .= '</br></br>';
        $content .= $review_photos_content;

        return $content;
    }

    /**
     * Internally registers pages we want to fake. Array key is the slug under which it is being available from the frontend
     * @return mixed
     */
    private function get_fake_pages()
    {
        $content = $this->get_page_content();
        //http://example.com/helpie-components
        $fake_pages['review-ui-components'] = array(
            'title' => 'Review UI Components',
            'content' => $content,
        );

        return $fake_pages;
    }

    /**
     * Fakes get posts result
     *
     * @param $posts
     *
     * @return array|null
     */
    public function fake_pages($posts)
    {
        global $wp, $wp_query;
        $fake_pages = $this->get_fake_pages();
        $fake_pages_slugs = array();
        foreach ($fake_pages as $slug => $fp) {
            $fake_pages_slugs[] = $slug;
        }
        if (true === in_array(strtolower($wp->request), $fake_pages_slugs)
            || (true === isset($wp->query_vars['page_id'])
                && true === in_array(strtolower($wp->query_vars['page_id']), $fake_pages_slugs)
            )
        ) {
            if (true === in_array(strtolower($wp->request), $fake_pages_slugs)) {
                $fake_page = strtolower($wp->request);
            } else {
                $fake_page = strtolower($wp->query_vars['page_id']);
            }
            $posts = null;
            $posts[] = $this->create_fake_page($fake_page, $fake_pages[$fake_page]);
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
            $wp_query->is_home = false;
            $wp_query->is_archive = false;
            $wp_query->is_category = false;
            $wp_query->is_fake_page = true;
            $wp_query->fake_page = $wp->request;
            //Longer permalink structures may not match the fake post slug and cause a 404 error so we catch the error here
            unset($wp_query->query["error"]);
            $wp_query->query_vars["error"] = "";
            $wp_query->is_404 = false;
        }

        return $posts;
    }

    /**
     * Creates virtual fake page
     *
     * @param $pagename
     * @param $page
     *
     * @return stdClass
     */
    private function create_fake_page($pagename, $page)
    {
        $post = new stdClass;
        $post->post_author = 1;
        $post->post_name = $pagename;
        $post->guid = get_bloginfo('wpurl') . '/' . $pagename;
        $post->post_title = $page['title'];
        $post->post_content = $page['content'];
        $post->post_type = 'page';
        $post->ID = -1;
        $post->post_status = 'static';
        $post->comment_status = 'closed';
        $post->ping_status = 'closed';
        $post->comment_count = 0;
        $post->post_date = current_time('mysql');
        $post->post_date_gmt = current_time('mysql', 1);

        return $post;
    }
}

new SCRFakePages();
