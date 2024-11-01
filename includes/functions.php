<?php

use \StarcatReview\Includes\Settings\SCR_Getter;

function scr_get_overall_rating($post_id)
{
    $args['items'] = scr_get_stat_args($post_id);
    $args['stat_type'] = 'post_stat';

    $args = array_merge(SCR_Getter::get_stat_default_args(), $args);

    $controller = new \StarcatReview\App\Components\Stats\Controller($args);
    $rating = $controller->get_rating();

    $overall_rating = [
        'overall' => [
            'rating' => (isset($rating['overall']['rating'])) ? $rating['overall']['rating'] : 0,
            'score' => (isset($rating['overall']['score'])) ? $rating['overall']['score'] : 0,
        ],
        'dom' => (!empty($rating['dom'])) ? $rating['dom'] : '',
    ];

    return $overall_rating;
}

/*
 * array $components [ 'stats', 'prosandcons', 'votes']
 * by default it returns comments of stats
 * returns comments of components
 */
function scr_get_comments_args($components = ['stats'], $query_args = [])
{
    return apply_filters('scr_comments_args', $components, $query_args);
}

/*
 * string $type
 * there are three types of stat available post_stat, author_stat and comment_stat
 * by default return post-stat
 * returns stat items args
 */

function scr_get_stat_args($post_id, $type = 'post_stat')
{
    return apply_filters('scr_stat_args', $post_id, $type);
}

/*
 * current uses cases of this function are reply comment and comments_factory to generate to comments
 */

function scr_get_comment($comment_id, $review = [])
{
    return apply_filters('scr_comment', $comment_id, $review);
}

function scr_get_user_reviews($post_id, $parent = true)
{
    $args = [
        'post_id' => $post_id,
        'type' => [SCR_COMMENT_TYPE, 'starcat_review'],
        'status' => 'approve',
    ];

    if ($parent) {
        $args['parent'] = 0;
    }

    $comments = get_comments($args);

    foreach ($comments as $comment) {
        $comment->reviews = get_comment_meta($comment->comment_ID, SCR_COMMENT_META, true);
    }

    return $comments;
}

function scr_get_user_reviews_count($post_id, $parent = true)
{
    $args = [
        'post_id' => $post_id,
        'type' => [SCR_COMMENT_TYPE, 'starcat_review'],
        'status' => 'approve',
    ];

    if ($parent) {
        $args['parent'] = 0;
    }

    $comments_count = count(get_comments($args));

    // error_log('$post_id : ' . $post_id);
    // error_log('$comments_count : ' . $comments_count);

    return $comments_count;
}

function scr_get_trend_score($post_id)
{
    $args = [
        'post_id' => $post_id,
        'type' => [SCR_COMMENT_TYPE, 'starcat_review'],
        'date_query' => array(
            'after' => '4 weeks ago',
            'before' => 'tomorrow',
            'inclusive' => true,
        ),
    ];

    $comments_count_last_4_weeks = count(get_comments($args));

    $trendScore = $comments_count_last_4_weeks;
    return $trendScore;
}
