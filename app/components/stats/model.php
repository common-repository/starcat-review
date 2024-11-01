<?php

namespace StarcatReview\App\Components\Stats;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\Stats\Model')) {
    class Model
    {
        public function get_viewProps($args)
        {
            $this->collection = $this->get_collectionProps($args);
            $this->items = $this->get_itemsProps($args);

            $view_props = [
                'collection' => $this->collection,
                'items' => $this->items,
            ];

            return $view_props;
        }

        public function get_collectionProps($args)
        {
            $collection = [
                'singularity' => $args['singularity'], // single or multiple
                'type' => $args['type'], // star, bar or circle
                'show_stats' => ['all'],
                'source_type' => $args['source_type'], // image or icon
                'icons' => $args['icons'],
                'images' => $args['images'],
                'animate' => $args['animate'],
                'limit' => $args['limit'],
                'show_rating_label' => $args['show_rating_label'],
                'no_rated_message' => 'Not Rated Yet !!!',
                'steps' => $args['steps'], // full or half or progress
            ];

            $collection = $this->get_icons($collection);
            $collection['stat_type'] = isset($args['stat_type']) && !empty($args['stat_type']) ? $args['stat_type'] : 'comment_stat';

            return $collection;
        }

        public function get_itemsProps($args)
        {
            $itemsProps = [];

            if (isset($args['items']) && !empty($args['items'])) {

                $overall_stat = $this->get_stat($args['items']['overall']);
                if ($this->collection['singularity'] == 'multiple' && count($args['items']['stats']) > 1) {
                    $itemsProps['overall'] = $overall_stat;
                }
                if ($this->collection['stat_type'] == 'post_stat') {
                    return ['overall' => $overall_stat];
                }

                foreach ($args['items']['stats'] as $stat_key => $stat_value) {
                    $itemsProps[$stat_key] = $this->get_stat($stat_value);
                }
            }

            return $itemsProps;
        }

        protected function get_stat($rating)
        {
            return [
                'rating' => $rating,
                'score' => $this->get_stat_score($rating),
            ];
        }

        protected function get_icons($collection)
        {
            $image = SCR_URL . 'includes/assets/img/tomato.png';

            $image_outline = SCR_URL . 'includes/assets/img/tomato-outline.png';

            $collection['icon'] = (isset($collection['images']['image']['thumbnail'])) ? $collection['images']['image']['thumbnail'] : $image;

            $collection['outline_icon'] = (isset($collection['images']['image-outline']['thumbnail'])) ? $collection['images']['image-outline']['thumbnail'] : $image_outline;

            if ($collection['source_type'] == 'icon') {
                $collection['icon'] = $collection['icons'] . ' icon';
                $collection['outline_icon'] = $collection['icons'] . ' outline icon';
            }

            return $collection;
        }

        protected function get_stat_score($stat_value)
        {
            $collection = $this->collection;

            $stat_score = $stat_value / (100 / $collection['limit']);

            $stat_score = $collection['steps'] == "precise" ? number_format($stat_score, 1) : round($stat_score, 1);

            return $stat_score;
        }

    } // END CLASS

}
