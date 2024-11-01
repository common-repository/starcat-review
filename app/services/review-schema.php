<?php

namespace StarcatReview\App\Services;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly



use Spatie\SchemaOrg\Schema;

if (!class_exists('\StarcatReview\App\Services\Review_Schema')) {
    class Review_Schema
    {
        public function get_schema($args)
        {
            $get_review_scripts = $this->get_product_schema($args);
            return $get_review_scripts;
        }

        protected function get_product_schema($args)
        {
            // generate product review
            $post = $args['post'];
            $post_comments = isset($args['comments']) ? $args['comments'] : [];
            $post_ratings = isset($args['ratings']) ? $args['ratings'] : array();
            // $reviews_schema = $this->get_reviews_schema($post_comments);
            $get_overall_ratings = count($post_ratings) > 0 ? $post_ratings['overall']['rating'] : 0;

            $get_overall_ratings_score = count($post_ratings) > 0 ? $post_ratings['overall']['score'] : 0;

            //get min and max ratings
            $rating = $this->get_ratings($post_comments);

            $schema_reviews = '{
                "@context": "http://schema.org",
                "@type": "Product",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "' . $get_overall_ratings_score . '",
                    "reviewCount": "' . $post->comment_count . '"
                },
                "description": "' . $post->post_content . '",
                "name": "' . $post->post_title . '",
                "image": "' . $args['featured_image_url'] . '"
                }';

            /****
             *
             *  "review": {
            "@type": "Review",
            "reviewRating": {
            "@type": "Rating",
            "ratingValue": "' . $rating['max_value'] . '",
            "bestRating": "' . $rating['max_value'] . '",
            "worstRating": "' . $rating['min_value'] . '"
            },
            "author": {
            "@type": "Person",
            "name": "' . $args['author_name'] . '"
            }
            },
             *
             *
             *****/

            // ->review(
            //     Schema::review()
            //         ->itemReviewed(
            //             Schema::Thing()
            //                 ->name('Blog')
            //         )
            //         ->reviewRating(
            //             Schema::rating()
            //                 ->ratingValue(10)
            //                 ->bestRating($rating['max_value'])
            //                 ->worstRating($rating['min_value'])
            //         )
            //         ->author(
            //             Schema::person()
            //                 ->name($args['author_name'])
            //         )
            // );

            // ->reviewRating(
            //     Schema::rating()
            //         ->ratingValue($review['rating'])
            //         ->bestRating($stats_ratings['max_rating'])
            //         ->worstRating($stats_ratings['min_rating'])
            // )
            // $schema_review = Schema::product()
            //     ->review($reviews_schema);

            // $schema_review =  Schema::blogPosting()
            //     ->itemReviewed()
            //     ->image($args['featured_image_url'])
            //     ->headline($post->post_title)
            //     ->url($post->guid)
            //     ->dateCreated($post->post_date)
            //     ->datePublished($post->post_date)
            //     ->dateModified($post->post_modified)
            //     ->inLanguage('en-US')
            //     ->author(
            //         Schema::person()
            //             ->name($args['author_name'])
            //     )
            //     ->publisher($this->get_publisher_schema($args))
            //     ->articleBody($post->post_content)
            //     ->aggregateRating(
            //         Schema::aggregateRating()
            //             ->ratingValue($get_overall_ratings)
            //             ->ratingCount(isset($post->comment_count) ? $post->comment_count : 0)
            //     );

            return $schema_reviews;
        }

        protected function get_reviews_schema($args)
        {
            $review_schema = array();
            foreach ($args as $comment) {
                $comment_date = date('Y-n-j', strtotime($comment->comment_date));
                $review = $comment->reviews;
                $stats_ratings = $this->get_min_max_ratings($review['stats']);
                $schema_review = Schema::review()
                    ->name($review['title'])
                    ->reviewBody($review['description'])
                    ->datePublished($comment_date)
                    ->reviewRating(
                        Schema::rating()
                            ->ratingValue($review['rating'])
                            ->bestRating($stats_ratings['max_rating'])
                            ->worstRating($stats_ratings['min_rating'])
                    )
                    ->author(
                        Schema::person()
                            ->name($comment->comment_author)
                    );
                $review_schema[] = $schema_review;
            }
            return $review_schema;
        }

        protected function get_article_schema($args)
        {
            // need to implement
        }

        protected function get_breadcrump_schema($args)
        {
            // need to implement
        }

        protected function get_publisher_schema($args)
        {

            $org_logo = SCR_URL . 'includes/assets/img/tomato.png';
            $publisher = Schema::organization()
                ->name('pauple studios')
                ->logo("http://via.placeholder.com/640x360");
            return $publisher;
        }

        protected function get_ratings($args)
        {

            if (count($args) > 0) {
                $ratings = array();
                foreach ($args as $comment) {
                    $reviews = isset($comment->reviews) ? $comment->reviews : array();
                    if (isset($reviews)) {
                        $get_comment_rating = isset($reviews['rating']) ? $reviews['rating'] : 0;
                        $ratings[] = $get_comment_rating;
                    }
                }

                $rating_info = array(
                    'min_value' => min($ratings),
                    'max_value' => max($ratings),
                );
            } else {
                $rating_info = array(
                    'min_value' => 0,
                    'max_value' => 0,
                );
            }
            return $rating_info;
        }

        protected function get_min_max_ratings($args)
        {
            //get the min and max ratings
            if (count($args) > 0) {

                $ratings = array();
                foreach ($args as $stat) {
                    $ratings[] = $stat['rating'];
                }
                $min_ratings = min($ratings);
                $max_ratings = max($ratings);
                return array(
                    'min_rating' => $min_ratings,
                    'max_rating' => $max_ratings,
                );
            } else {
                return array(
                    'min_rating' => 0,
                    'max_rating' => 0,
                );
            }
        }
    }
}