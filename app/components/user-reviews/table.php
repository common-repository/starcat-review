<?php

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

use StarcatReview\Includes\Settings\SCR_Getter;

class UR_List_Table extends WP_List_Table
{

    public $checkbox = true;

    public $pending_count = array();

    public $extra_items;

    private $user_can;

    private $comments_of_stats = array();

    private $stat_args;

    /**
     * Constructor.
     *
     * @since 3.1.0
     *
     * @see WP_List_Table::__construct() for more information on default arguments.
     *
     * @global int $post_id
     *
     * @param array $args An associative array of arguments.
     */
    public function __construct($args = array())
    {
        global $post_id;

        $post_id = isset($_REQUEST['p']) ? absint($_REQUEST['p']) : 0;

        if (get_option('show_avatars')) {
            add_filter('comment_author', array($this, 'floated_admin_avatar'), 10, 2);
        }

        parent::__construct(
            array(
                'plural' => 'comments',
                'singular' => 'comment',
                'ajax' => true,
                'screen' => isset($args['screen']) ? $args['screen'] : null,
            )
        );

        $this->comments_of_stats = scr_get_comments_args(['stats']);
        $this->stat_args = SCR_Getter::get_stat_default_args();
    }

    public function floated_admin_avatar($name, $comment_ID)
    {
        $comment = get_comment($comment_ID);
        $avatar = get_avatar($comment->user_id, 32);
        return "$avatar $name";
    }

    /**
     * @return bool
     */
    public function ajax_user_can()
    {
        return current_user_can('edit_posts');
    }

    /**
     * @global int    $post_id
     * @global string $comment_status
     * @global string $search
     * @global string $comment_type
     */
    public function prepare_items()
    {
        global $post_id, $comment_status, $search, $comment_type;

        $comment_status = isset($_REQUEST['comment_status']) ? sanitize_text_field($_REQUEST['comment_status']) : 'all';
        if (!in_array($comment_status, array('all', 'mine', 'moderated', 'approved', 'spam', 'trash'))) {
            $comment_status = 'all';
        }

        $comment_type = 'review';

        $search = (isset($_REQUEST['s'])) ? sanitize_text_field(wp_unslash($_REQUEST['s'])) : '';

        $post_type = (isset($_REQUEST['post_type'])) ? sanitize_key($_REQUEST['post_type']) : '';

        $user_id = (isset($_REQUEST['user_id'])) ? intval($_REQUEST['user_id']) : '';

        $orderby = (isset($_REQUEST['orderby'])) ? sanitize_text_field($_REQUEST['orderby']) : '';
        $order = (isset($_REQUEST['order'])) ? sanitize_text_field($_REQUEST['order']) : '';

        $comments_per_page = $this->get_per_page($comment_status);

        $doing_ajax = wp_doing_ajax();

        if (isset($_REQUEST['number'])) {
            $number = intval($_REQUEST['number']);
        } else {
            $number = $comments_per_page + min(8, $comments_per_page); // Grab a few extra
        }

        $page = $this->get_pagenum();

        if (isset($_REQUEST['start'])) {
            $start = sanitize_text_field($_REQUEST['start']);
        } else {
            $start = ($page - 1) * $comments_per_page;
        }

        if ($doing_ajax && isset($_REQUEST['offset'])) {
            $start += sanitize_text_field($_REQUEST['offset']);
        }

        $status_map = array(
            'mine' => '',
            'moderated' => 'hold',
            'approved' => 'approve',
            'all' => '',
        );

        $args = array(
            'status' => isset($status_map[$comment_status]) ? $status_map[$comment_status] : $comment_status,
            'search' => $search,
            'user_id' => $user_id,
            'offset' => $start,
            'number' => $number,
            'post_id' => $post_id,
            'type' => $comment_type,
            'orderby' => $orderby,
            'order' => $order,
            'post_type' => $post_type,
        );

        /**
         * Filters the arguments for the comment query in the comments list table.
         *
         * @since 5.1.0
         *
         * @param array $args An array of get_comments() arguments.
         */
        $args = apply_filters('comments_list_table_query_args', $args);

        $_comments = get_comments($args);
        if (is_array($_comments)) {
            update_comment_cache($_comments);

            $this->items = array_slice($_comments, 0, $comments_per_page);
            $this->extra_items = array_slice($_comments, $comments_per_page);

            $_comment_post_ids = array_unique(wp_list_pluck($_comments, 'comment_post_ID'));

            $this->pending_count = get_pending_comments_num($_comment_post_ids);
        }

        $total_comments = get_comments(
            array_merge(
                $args,
                array(
                    'count' => true,
                    'offset' => 0,
                    'number' => 0,
                )
            )
        );

        $this->set_pagination_args(
            array(
                'total_items' => $total_comments,
                'per_page' => $comments_per_page,
            )
        );
    }

    /**
     * @param string $comment_status
     * @return int
     */
    public function get_per_page($comment_status = 'all')
    {
        $comments_per_page = $this->get_items_per_page('user_reviews_per_page');
        /**
         * Filters the number of comments listed per page in the comments list table.
         *
         * @since 2.6.0
         *
         * @param int    $comments_per_page The number of comments to list per page.
         * @param string $comment_status    The comment status name. Default 'All'.
         */
        return apply_filters('comments_per_page', $comments_per_page, $comment_status);
    }

    /**
     * @global string $comment_status
     */
    public function no_items()
    {
        global $comment_status;

        if ('moderated' === $comment_status) {
            _e('No reviews awaiting moderation.');
        } else {
            _e('No reviews found.');
        }
    }

    /**
     * @global int $post_id
     * @global string $comment_status
     * @global string $comment_type
     */
    public function get_views()
    {
        global $post_id, $comment_status, $comment_type;

        $status_links = array();
        $num_comments = ($post_id) ? $this->get_reviews_count($post_id) : $this->get_reviews_count();

        $stati = array(
            /* translators: %s: Number of comments. */
            'all' => _nx_noop(
                'All <span class="count">(%s)</span>',
                'All <span class="count">(%s)</span>',
                'comments'
            ), // singular not used

            /* translators: %s: Number of comments. */
            'mine' => _nx_noop(
                'Mine <span class="count">(%s)</span>',
                'Mine <span class="count">(%s)</span>',
                'comments'
            ),

            /* translators: %s: Number of comments. */
            'awaiting_moderation' => _nx_noop(
                'Pending <span class="count">(%s)</span>',
                'Pending <span class="count">(%s)</span>',
                'comments'
            ),

            /* translators: %s: Number of comments. */
            'approved' => _nx_noop(
                'Approved <span class="count">(%s)</span>',
                'Approved <span class="count">(%s)</span>',
                'comments'
            ),

            /* translators: %s: Number of comments. */
            'spam' => _nx_noop(
                'Spam <span class="count">(%s)</span>',
                'Spam <span class="count">(%s)</span>',
                'comments'
            ),

            /* translators: %s: Number of comments. */
            'trash' => _nx_noop(
                'Trash <span class="count">(%s)</span>',
                'Trash <span class="count">(%s)</span>',
                'comments'
            ),
        );

        if (!EMPTY_TRASH_DAYS) {
            unset($stati['trash']);
        }

        $link = admin_url('admin.php?page=scr-reviews-comment');
        if (!empty($comment_type) && 'all' != $comment_type) {
            $link = add_query_arg('type', $comment_type, $link);
        }

        foreach ($stati as $status => $label) {
            $current_link_attributes = '';
            $link_status = ('awaiting_moderation' === $status) ? 'moderated' : $status;

            if ($link_status === $comment_status) {
                $current_link_attributes = ' class="current" aria-current="page"';
            }

            if ('mine' === $status) {
                $current_user_id = get_current_user_id();
                $num_comments->mine = get_comments(
                    array(
                        'post_id' => $post_id ? $post_id : 0,
                        'user_id' => $current_user_id,
                        'type' => $comment_type,
                        'count' => true,
                    )
                );
                $link = add_query_arg([
                    'user_id' => $current_user_id,
                    'type' => $comment_type,
                ], $link);
            } else {
                $link = remove_query_arg('user_id', $link);
            }

            if (!isset($num_comments->$status)) {
                $num_comments->$status = 0;
            }

            $link = add_query_arg('comment_status', $link_status, $link);
            if ($post_id) {
                $link = add_query_arg('p', absint($post_id), $link);
            }
            /*
            // I toyed with this, but decided against it. Leaving it in here in case anyone thinks it is a good idea. ~ Mark
            if ( !empty( $_REQUEST['s'] ) )
            $link = add_query_arg( 's', esc_attr( wp_unslash( $_REQUEST['s'] ) ), $link );
             */

            $status_links[$status] = "<a href='$link'$current_link_attributes>" . sprintf(
                translate_nooped_plural($label, $num_comments->$status),
                sprintf(
                    '<span class="%s-count">%s</span>',
                    ('moderated' === $status) ? 'pending' : $status,
                    number_format_i18n($num_comments->$status)
                )
            ) . '</a>';
        }

        /**
         * Filters the comment status links.
         *
         * @since 2.5.0
         * @since 5.1.0 The 'Mine' link was added.
         *
         * @param string[] $status_links An associative array of fully-formed comment status links. Includes 'All', 'Mine',
         *                              'Pending', 'Approved', 'Spam', and 'Trash'.
         */
        $html_links = apply_filters('comment_status_links', $status_links);
        $html = '<ul class="subsubsub">';
        foreach ($html_links as $link => $value) {
            $html .= '<li class="' . $link . '">';
            $html .= $value;
            $html .= '</li>';
        }
        $html .= '</ul>';

        echo $html;
    }

    public function get_reviews_count($post_id = 0)
    {
        global $wpdb;

        $post_id = (int) $post_id;
        $where = $wpdb->prepare('WHERE comment_type IN (%s, %s)', 'review', 'starcat_review');
        if ($post_id > 0) {
            $where = $wpdb->prepare('WHERE comment_type IN (%s, %s) AND comment_post_ID = %d', 'review', 'starcat_review', $post_id);
        }

        $totals = (array) $wpdb->get_results("SELECT comment_approved, COUNT( * ) AS total
        FROM {$wpdb->comments}
        {$where}
        GROUP BY comment_approved", ARRAY_A);

        $comment_count = array(
            'approved' => 0,
            'awaiting_moderation' => 0,
            'spam' => 0,
            'trash' => 0,
            'post-trashed' => 0,
            'total_comments' => 0,
            'all' => 0,
        );

        foreach ($totals as $row) {
            switch ($row['comment_approved']) {
                case 'trash':
                    $comment_count['trash'] = $row['total'];
                    break;
                case 'post-trashed':
                    $comment_count['post-trashed'] = $row['total'];
                    break;
                case 'spam':
                    $comment_count['spam'] = $row['total'];
                    $comment_count['total_comments'] += $row['total'];
                    break;
                case '1':
                    $comment_count['approved'] = $row['total'];
                    $comment_count['total_comments'] += $row['total'];
                    $comment_count['all'] += $row['total'];
                    break;
                case '0':
                    $comment_count['awaiting_moderation'] = $row['total'];
                    $comment_count['total_comments'] += $row['total'];
                    $comment_count['all'] += $row['total'];
                    break;
                default:
                    break;
            }
        }

        return (object) $comment_count;
    }

    /**
     * @global string $comment_status
     *
     * @return array
     */
    protected function get_bulk_actions()
    {
        global $comment_status;

        $actions = array();
        if (in_array($comment_status, array('all', 'approved'))) {
            $actions['unapprove'] = __('Unapprove');
        }
        if (in_array($comment_status, array('all', 'moderated'))) {
            $actions['approve'] = __('Approve');
        }
        if (in_array($comment_status, array('all', 'moderated', 'approved', 'trash'))) {
            $actions['spam'] = _x('Mark as Spam', 'comment');
        }

        if ('trash' === $comment_status) {
            $actions['untrash'] = __('Restore');
        } elseif ('spam' === $comment_status) {
            $actions['unspam'] = _x('Not Spam', 'comment');
        }

        if (in_array($comment_status, array('trash', 'spam')) || !EMPTY_TRASH_DAYS) {
            $actions['delete'] = __('Delete Permanently');
        } else {
            $actions['trash'] = __('Move to Trash');
        }

        return $actions;
    }

    /**
     * @global string $comment_status
     * @global string $comment_type
     *
     * @param string $which
     */
    protected function extra_tablenav($which)
    {
        global $comment_status, $comment_type;
        static $has_items;

        if (!isset($has_items)) {
            $has_items = $this->has_items();
        }
        ?>
		<div class="alignleft actions">
		<?php
if ('top' === $which) {
            ?>
	<label class="screen-reader-text" for="filter-by-comment-type"><?php _e('Filter by comment type');?></label>
	<select id="filter-by-comment-type" name="comment_type">
		<option value=""><?php _e('All comment types');?></option>
			<?php
/**
             * Filters the comment types dropdown menu.
             *
             * @since 2.7.0
             *
             * @param string[] $comment_types An array of comment types. Accepts 'Comments', 'Pings'.
             */
            $comment_types = apply_filters(
                'admin_comment_types_dropdown',
                array(
                    'comment' => __('Comments'),
                    'pings' => __('Pings'),
                )
            );

            foreach ($comment_types as $type => $label) {
                echo "\t" . '<option value="' . esc_attr($type) . '"' . selected($comment_type, $type, false) . ">$label</option>\n";
            }
            ?>
	</select>
			<?php
/**
             * Fires just before the Filter submit button for comment types.
             *
             * @since 3.5.0
             */
            do_action('restrict_manage_comments');
            submit_button(__('Filter'), '', 'filter_action', false, array('id' => 'post-query-submit'));
        }

        if (('spam' === $comment_status || 'trash' === $comment_status) && current_user_can('moderate_comments') && $has_items) {
            wp_nonce_field('bulk-destroy', '_destroy_nonce');
            $title = ('spam' === $comment_status) ? esc_attr__('Empty Spam') : esc_attr__('Empty Trash');
            submit_button($title, 'apply', 'delete_all', false);
        }
        /**
         * Fires after the Filter submit button for comment types.
         *
         * @since 2.5.0
         *
         * @param string $comment_status The comment status name. Default 'All'.
         */
        do_action('manage_comments_nav', $comment_status);
        echo '</div>';
    }

    /**
     * @return string|false
     */
    public function current_action()
    {
        if (isset($_REQUEST['delete_all']) || isset($_REQUEST['delete_all2'])) {
            return 'delete_all';
        }

        return parent::current_action();
    }

    /**
     * @global int $post_id
     *
     * @return array
     */
    public function get_columns()
    {
        global $post_id;

        $columns = array();

        if ($this->checkbox) {
            $columns['cb'] = '<input type="checkbox" />';
        }

        $columns['author'] = __('Author');
        $columns['rating'] = __('Rating', SCR_DOMAIN);
        $columns['comment'] = __('Review', SCR_DOMAIN);

        if (!$post_id) {
            /* translators: Column name or table row header. */
            $columns['response'] = __('In Response To');
        }

        $columns['date'] = _x('Submitted On', 'column name');

        return $columns;
    }

    /**
     * @return array
     */
    protected function get_sortable_columns()
    {
        return array(
            'author' => 'comment_author',
            'response' => 'comment_post_ID',
            'date' => 'comment_date',
        );
    }

    /**
     * Get the name of the default primary column.
     *
     * @since 4.3.0
     *
     * @return string Name of the default primary column, in this case, 'comment'.
     */
    protected function get_default_primary_column_name()
    {
        return 'comment';
    }

    /**
     * Displays the comments table.
     *
     * Overrides the parent display() method to render extra comments.
     *
     * @since 3.1.0
     */
    public function display()
    {
        wp_nonce_field('fetch-list-' . get_class($this), '_ajax_fetch_list_nonce');

        $this->display_tablenav('top');

        $this->screen->render_screen_reader_content('heading_list');

        ?>
<table class="wp-list-table <?php echo implode(' ', $this->get_table_classes()); ?>">
	<thead>
	<tr>
		<?php $this->print_column_headers();?>
	</tr>
	</thead>

	<tbody id="the-comment-list" data-wp-lists="list:comment">
		<?php $this->display_rows_or_placeholder();?>
	</tbody>

	<tbody id="the-extra-comment-list" data-wp-lists="list:comment" style="display: none;">
		<?php
/*
         * Back up the items to restore after printing the extra items markup.
         * The extra items may be empty, which will prevent the table nav from displaying later.
         */
        $items = $this->items;
        $this->items = $this->extra_items;
        $this->display_rows_or_placeholder();
        $this->items = $items;
        ?>
	</tbody>

	<tfoot>
	<tr>
		<?php $this->print_column_headers(false);?>
	</tr>
	</tfoot>

</table>
		<?php

        $this->display_tablenav('bottom');
    }

    /**
     * @global WP_Post    $post    Global post object.
     * @global WP_Comment $comment Global comment object.
     *
     * @param WP_Comment $item
     */
    public function single_row($item)
    {
        global $post, $comment;

        $comment = $item;

        $the_comment_class = wp_get_comment_status($comment);
        if (!$the_comment_class) {
            $the_comment_class = '';
        }
        $the_comment_class = join(' ', get_comment_class($the_comment_class, $comment, $comment->comment_post_ID));

        if ($comment->comment_post_ID > 0) {
            $post = get_post($comment->comment_post_ID);
        }
        $this->user_can = current_user_can('edit_comment', $comment->comment_ID);

        echo "<tr id='comment-$comment->comment_ID' class='$the_comment_class'>";
        $this->single_row_columns($comment);
        echo "</tr>\n";

        unset($GLOBALS['post'], $GLOBALS['comment']);
    }

    /**
     * Generate and display row actions links.
     *
     * @since 4.3.0
     *
     * @global string $comment_status Status for the current listed comments.
     *
     * @param WP_Comment $comment     The comment object.
     * @param string     $column_name Current column name.
     * @param string     $primary     Primary column name.
     * @return string|void Comment row actions output.
     */
    protected function handle_row_actions($comment, $column_name, $primary)
    {
        global $comment_status;

        if ($primary !== $column_name) {
            return '';
        }

        if (!$this->user_can) {
            return;
        }

        $the_comment_status = wp_get_comment_status($comment);

        $out = '';

        $del_nonce = esc_html('_wpnonce=' . wp_create_nonce("delete-comment_$comment->comment_ID"));
        $approve_nonce = esc_html('_wpnonce=' . wp_create_nonce("approve-comment_$comment->comment_ID"));

        $url = "comment.php?c=$comment->comment_ID";

        $approve_url = esc_url($url . "&action=approvecomment&$approve_nonce");
        $unapprove_url = esc_url($url . "&action=unapprovecomment&$approve_nonce");
        $spam_url = esc_url($url . "&action=spamcomment&$del_nonce");
        $unspam_url = esc_url($url . "&action=unspamcomment&$del_nonce");
        $trash_url = esc_url($url . "&action=trashcomment&$del_nonce");
        $untrash_url = esc_url($url . "&action=untrashcomment&$del_nonce");
        $delete_url = esc_url($url . "&action=deletecomment&$del_nonce");

        // Preorder it: Approve | Reply | Quick Edit | Edit | Spam | Trash.
        $actions = array(
            'approve' => '',
            'unapprove' => '',
            'reply' => '',
            'quickedit' => '',
            'edit' => '',
            'spam' => '',
            'unspam' => '',
            'trash' => '',
            'untrash' => '',
            'delete' => '',
        );

        // Not looking at all comments.
        if ($comment_status && 'all' != $comment_status) {
            if ('approved' === $the_comment_status) {
                $actions['unapprove'] = sprintf(
                    '<a href="%s" data-wp-lists="%s" class="vim-u vim-destructive aria-button-if-js" aria-label="%s">%s</a>',
                    $unapprove_url,
                    "delete:the-comment-list:comment-{$comment->comment_ID}:e7e7d3:action=dim-comment&amp;new=unapproved",
                    esc_attr__('Unapprove this comment'),
                    __('Unapprove')
                );
            } elseif ('unapproved' === $the_comment_status) {
                $actions['approve'] = sprintf(
                    '<a href="%s" data-wp-lists="%s" class="vim-a vim-destructive aria-button-if-js" aria-label="%s">%s</a>',
                    $approve_url,
                    "delete:the-comment-list:comment-{$comment->comment_ID}:e7e7d3:action=dim-comment&amp;new=approved",
                    esc_attr__('Approve this comment'),
                    __('Approve')
                );
            }
        } else {
            $actions['approve'] = sprintf(
                '<a href="%s" data-wp-lists="%s" class="vim-a aria-button-if-js" aria-label="%s">%s</a>',
                $approve_url,
                "dim:the-comment-list:comment-{$comment->comment_ID}:unapproved:e7e7d3:e7e7d3:new=approved",
                esc_attr__('Approve this comment'),
                __('Approve')
            );

            $actions['unapprove'] = sprintf(
                '<a href="%s" data-wp-lists="%s" class="vim-u aria-button-if-js" aria-label="%s">%s</a>',
                $unapprove_url,
                "dim:the-comment-list:comment-{$comment->comment_ID}:unapproved:e7e7d3:e7e7d3:new=unapproved",
                esc_attr__('Unapprove this comment'),
                __('Unapprove')
            );
        }

        if ('spam' !== $the_comment_status) {
            $actions['spam'] = sprintf(
                '<a href="%s" data-wp-lists="%s" class="vim-s vim-destructive aria-button-if-js" aria-label="%s">%s</a>',
                $spam_url,
                "delete:the-comment-list:comment-{$comment->comment_ID}::spam=1",
                esc_attr__('Mark this comment as spam'),
                /* translators: "Mark as spam" link. */
                _x('Spam', 'verb')
            );
        } elseif ('spam' === $the_comment_status) {
            $actions['unspam'] = sprintf(
                '<a href="%s" data-wp-lists="%s" class="vim-z vim-destructive aria-button-if-js" aria-label="%s">%s</a>',
                $unspam_url,
                "delete:the-comment-list:comment-{$comment->comment_ID}:66cc66:unspam=1",
                esc_attr__('Restore this comment from the spam'),
                _x('Not Spam', 'comment')
            );
        }

        if ('trash' === $the_comment_status) {
            $actions['untrash'] = sprintf(
                '<a href="%s" data-wp-lists="%s" class="vim-z vim-destructive aria-button-if-js" aria-label="%s">%s</a>',
                $untrash_url,
                "delete:the-comment-list:comment-{$comment->comment_ID}:66cc66:untrash=1",
                esc_attr__('Restore this comment from the Trash'),
                __('Restore')
            );
        }

        if ('spam' === $the_comment_status || 'trash' === $the_comment_status || !EMPTY_TRASH_DAYS) {
            $actions['delete'] = sprintf(
                '<a href="%s" data-wp-lists="%s" class="delete vim-d vim-destructive aria-button-if-js" aria-label="%s">%s</a>',
                $delete_url,
                "delete:the-comment-list:comment-{$comment->comment_ID}::delete=1",
                esc_attr__('Delete this comment permanently'),
                __('Delete Permanently')
            );
        } else {
            $actions['trash'] = sprintf(
                '<a href="%s" data-wp-lists="%s" class="delete vim-d vim-destructive aria-button-if-js" aria-label="%s">%s</a>',
                $trash_url,
                "delete:the-comment-list:comment-{$comment->comment_ID}::trash=1",
                esc_attr__('Move this comment to the Trash'),
                _x('Trash', 'verb')
            );
        }

        // if ('spam' !== $the_comment_status && 'trash' !== $the_comment_status) {
        //     $actions['edit'] = sprintf(
        //         '<a href="%s" aria-label="%s">%s</a>',
        //         "comment.php?action=editcomment&amp;c={$comment->comment_ID}",
        //         esc_attr__('Edit this comment'),
        //         __('Edit')
        //     );

        //     $format = '<button type="button" data-comment-id="%d" data-post-id="%d" data-action="%s" class="%s button-link" aria-expanded="false" aria-label="%s">%s</button>';

        //     $actions['quickedit'] = sprintf(
        //         $format,
        //         $comment->comment_ID,
        //         $comment->comment_post_ID,
        //         'edit',
        //         'vim-q comment-inline',
        //         esc_attr__('Quick edit this comment inline'),
        //         __('Quick&nbsp;Edit')
        //     );

        //     $actions['reply'] = sprintf(
        //         $format,
        //         $comment->comment_ID,
        //         $comment->comment_post_ID,
        //         'replyto',
        //         'vim-r comment-inline',
        //         esc_attr__('Reply to this comment'),
        //         __('Reply')
        //     );
        // }

        /** This filter is documented in wp-admin/includes/dashboard.php */
        $actions = apply_filters('comment_row_actions', array_filter($actions), $comment);

        $i = 0;
        $out .= '<div class="row-actions">';
        foreach ($actions as $action => $link) {
            ++$i;
            ((('approve' === $action || 'unapprove' === $action) && 2 === $i) || 1 === $i) ? $sep = '' : $sep = ' | ';

            // Reply and quickedit need a hide-if-no-js span when not added with ajax
            if (('reply' === $action || 'quickedit' === $action) && !wp_doing_ajax()) {
                $action .= ' hide-if-no-js';
            } elseif (($action === 'untrash' && $the_comment_status === 'trash') || ($action === 'unspam' && $the_comment_status === 'spam')) {
                if ('1' == get_comment_meta($comment->comment_ID, '_wp_trash_meta_status', true)) {
                    $action .= ' approve';
                } else {
                    $action .= ' unapprove';
                }
            }

            $out .= "<span class='$action'>$sep$link</span>";
        }
        $out .= '</div>';

        $out .= '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __('Show more details') . '</span></button>';

        return $out;
    }

    /**
     * @param WP_Comment $comment The comment object.
     */
    public function column_cb($comment)
    {
        if ($this->user_can) {
            ?>
		<label class="screen-reader-text" for="cb-select-<?php echo $comment->comment_ID; ?>"><?php _e('Select comment');?></label>
		<input id="cb-select-<?php echo $comment->comment_ID; ?>" type="checkbox" name="delete_comments[]" value="<?php echo $comment->comment_ID; ?>" />
			<?php
}
    }

    /**
     * @param WP_Comment $comment The comment object.
     */
    public function column_comment($comment)
    {
        echo '<div class="comment-author">';
        $this->column_author($comment);
        echo '</div>';

        if ($comment->comment_parent) {
            $parent = get_comment($comment->comment_parent);
            if ($parent) {
                $parent_link = esc_url(get_comment_link($parent));
                $name = get_comment_author($parent);
                printf(
                    /* translators: %s: Comment link. */
                    __('In reply to %s.'),
                    '<a href="' . $parent_link . '">' . $name . '</a>'
                );
            }
        }

        comment_text($comment);

        if ($this->user_can) {
            /** This filter is documented in wp-admin/includes/comment.php */
            $comment_content = apply_filters('comment_edit_pre', $comment->comment_content);
            ?>
		<div id="inline-<?php echo $comment->comment_ID; ?>" class="hidden">
			<textarea class="comment" rows="1" cols="1"><?php echo esc_textarea($comment_content); ?></textarea>
			<div class="author-email"><?php echo esc_attr($comment->comment_author_email); ?></div>
			<div class="author"><?php echo esc_attr($comment->comment_author); ?></div>
			<div class="author-url"><?php echo esc_attr($comment->comment_author_url); ?></div>
			<div class="comment_status"><?php echo $comment->comment_approved; ?></div>
		</div>
			<?php
}
    }

    /**
     * @global string $comment_status
     *
     * @param WP_Comment $comment The comment object.
     */
    public function column_author($comment)
    {
        global $comment_status;

        $author_url = get_comment_author_url($comment);

        $author_url_display = untrailingslashit(preg_replace('|^http(s)?://(www\.)?|i', '', $author_url));
        if (strlen($author_url_display) > 50) {
            $author_url_display = wp_html_excerpt($author_url_display, 49, '&hellip;');
        }

        echo '<strong>';
        comment_author($comment);
        echo '</strong><br />';
        if (!empty($author_url_display)) {
            printf('<a href="%s">%s</a><br />', esc_url($author_url), esc_html($author_url_display));
        }

        if ($this->user_can) {
            if (!empty($comment->comment_author_email)) {
                /** This filter is documented in wp-includes/comment-template.php */
                $email = apply_filters('comment_email', $comment->comment_author_email, $comment);

                if (!empty($email) && '@' !== $email) {
                    printf('<a href="%1$s">%2$s</a><br />', esc_url('mailto:' . $email), esc_html($email));
                }
            }

            $author_ip = get_comment_author_IP($comment);
            if ($author_ip) {
                $author_ip_url = add_query_arg(
                    array(
                        's' => $author_ip,
                        'mode' => 'detail',
                    ),
                    admin_url('edit-comments.php')
                );
                if ('spam' === $comment_status) {
                    $author_ip_url = add_query_arg('comment_status', 'spam', $author_ip_url);
                }
                printf('<a href="%1$s">%2$s</a>', esc_url($author_ip_url), esc_html($author_ip));
            }
        }
    }

    /**
     * @param WP_Comment $comment The comment object.
     */
    public function column_date($comment)
    {
        $submitted = sprintf(
            /* translators: 1: Comment date, 2: Comment time. */
            __('%1$s at %2$s'),
            /* translators: Comment date format. See https://secure.php.net/date */
            get_comment_date(__('Y/m/d'), $comment),
            /* translators: Comment time format. See https://secure.php.net/date */
            get_comment_date(__('g:i a'), $comment)
        );

        echo '<div class="submitted-on">';
        if ('approved' === wp_get_comment_status($comment) && !empty($comment->comment_post_ID)) {
            printf(
                '<a href="%s">%s</a>',
                esc_url(get_comment_link($comment)),
                $submitted
            );
        } else {
            echo $submitted;
        }
        echo '</div>';
    }

    /**
     * @param WP_Comment $comment The comment object.
     */

    public function column_rating($comment)
    {
        $rating = '---';

        if (isset($this->comments_of_stats[$comment->comment_ID]) && !empty($this->comments_of_stats[$comment->comment_ID])) {
            $stat_args = array_merge($this->stat_args, ['items' => $this->comments_of_stats[$comment->comment_ID]]);
            $stat_controller = new \StarcatReview\App\Components\Stats\Controller($stat_args);
            $rating = $stat_controller->get_view();
        }

        echo $rating;
    }

    /**
     * @param WP_Comment $comment The comment object.
     */
    public function column_response($comment)
    {
        $post = get_post();

        if (!$post) {
            return;
        }

        if (isset($this->pending_count[$post->ID])) {
            $pending_comments = $this->pending_count[$post->ID];
        } else {
            $_pending_count_temp = get_pending_comments_num(array($post->ID));
            $pending_comments = $_pending_count_temp[$post->ID];
            $this->pending_count[$post->ID] = $pending_comments;
        }

        if (current_user_can('edit_post', $post->ID)) {
            $post_link = "<a href='" . get_edit_post_link($post->ID) . "' class='comments-edit-item-link'>";
            $post_link .= esc_html(get_the_title($post->ID)) . '</a>';
        } else {
            $post_link = esc_html(get_the_title($post->ID));
        }

        echo '<div class="response-links">';
        if ('attachment' === $post->post_type) {
            $thumb = wp_get_attachment_image($post->ID, array(80, 60), true);
            if ($thumb) {
                echo $thumb;
            }
        }
        echo $post_link;
        $post_type_object = get_post_type_object($post->post_type);
        echo "<a href='" . get_permalink($post->ID) . "' class='comments-view-item-link'>" . $post_type_object->labels->view_item . '</a>';
        echo '<span class="post-com-count-wrapper post-com-count-', $post->ID, '">';
        ob_start();
        $this->comments_bubble($post->ID, $pending_comments);
        $bubble = ob_get_contents();
        ob_end_clean();
        echo str_replace('edit-comments.php?', 'admin.php?page=scr-reviews-comment&#038;', $bubble);
        echo '</span> ';
        echo '</div>';
    }

    /**
     * @param WP_Comment $comment     The comment object.
     * @param string     $column_name The custom column's name.
     */
    public function column_default($comment, $column_name)
    {
        /**
         * Fires when the default column output is displayed for a single row.
         *
         * @since 2.8.0
         *
         * @param string $column_name         The custom column's name.
         * @param int    $comment->comment_ID The custom column's unique ID number.
         */
        do_action('manage_comments_custom_column', $column_name, $comment->comment_ID);
    }

}

class UR_List_Table_Controller
{

    // class instance
    static $instance;

    // user_review WP_List_Table object
    public $ur_table;

    // class constructor
    public function __construct()
    {
        add_filter('set-screen-option', [__CLASS__, 'set_screen'], 10, 3);
        add_action('admin_menu', [$this, 'plugin_menu']);
    }

    public static function set_screen($status, $option, $value)
    {
        return $value;
    }

    public function plugin_menu()
    {

        $hook = add_menu_page(
            __('User Reviews', SCR_DOMAIN),
            __('User Reviews', SCR_DOMAIN) . ' ' . $this->get_review_pending_count_html(),
            'read',
            'scr-reviews-comment',
            [$this, 'plugin_settings_page'],
            'dashicons-format-status',
            '35'
        );

        add_action("load-$hook", [$this, 'screen_option']);
    }

    /**
     * Plugin settings page
     */
    public function plugin_settings_page()
    {
        $html = '<div class="wrap reviews-comment-wrap">';
        $html .= '<h1 class="wp-heading-inline"> ' . __("User Reviews", SCR_DOMAIN) . '</h1>';
        $html .= '<hr class="wp-header-end">';

        ob_start();
        $this->ur_table->prepare_items();
        $this->ur_table->get_views();
        $this->ur_table->display();
        $html .= ob_get_contents();
        ob_end_clean();
        $html .= '</div>';

        echo $html;
    }

    /**
     * Screen options
     */
    public function screen_option()
    {

        $option = 'per_page';

        $args = [
            'label' => __("Number of items per page:", SCR_DOMAIN),
            'default' => 5,
            'option' => 'user_reviews_per_page',
        ];

        add_screen_option($option, $args);

        $this->ur_table = new UR_List_Table();
    }

    /** Singleton instance */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function get_review_pending_count_html()
    {
        $count = $this->get_review_pending_count();
        $html = '';
        if ($count !== 0) {
            $html .= '<span class="awaiting-mod count-1">';
            $html .= '<span class="pending-count" aria-hidden="true">';
            $html .= $count;
            $html .= '</span>';
            $html .= '</span>';
        }

        return $html;
    }

    protected function get_review_pending_count()
    {
        global $wpdb;
        $count = 0;
        $where = $wpdb->prepare('WHERE comment_type IN (%s, %s)', 'review', 'starcat_review');
        $pending = $wpdb->get_results("SELECT comment_post_ID, COUNT(comment_ID) as num_comments FROM $wpdb->comments {$where} AND comment_approved = '0'", ARRAY_A);

        if (!empty($pending)) {
            $count = absint($pending[0]['num_comments']);
        }

        return $count;
    }
}

add_action('plugins_loaded', function () {
    UR_List_Table_Controller::get_instance();
});
