<?php

namespace StarcatReview\App\Components\ProsAndCons;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\StarcatReview\App\Components\ProsAndCons\Model')) {
    class Model
    {
        public function get_viewProps($args)
        {
            $view_props = [
                'collection' => $this->get_collection($args),
                'items' => $this->get_items($args),
            ];
            return $view_props;
        }

        protected function get_collection($args)
        {
            $pros_list = isset($args['collection']['pros-list']) ? $args['collection']['pros-list'] : [];
            $cons_list = isset($args['collection']['cons-list']) ? $args['collection']['cons-list'] : [];

            $collection = [
                'pros-list' => $pros_list,
                'cons-list' => $cons_list,
            ];

            return $collection;
        }

        protected function get_items($args)
        {

            // Return if empty
            if ($this->is_empty($args['items'])) {
                return [];
            }

            $pros = isset($args['items']['pros']) ? $$args['items']['pros'] : [];
            $cons = isset($args['items']['cons']) ? $$args['items']['cons'] : [];

            $pros = isset($args['items']['pros-list']) ? $args['items']['pros-list'] : $pros;
            $cons = isset($args['items']['cons-list']) ? $args['items']['cons-list'] : $cons;

            $itemsProps = [
                'pros' => $this->get_list($pros),
                'cons' => $this->get_list($cons),
            ];

            return $itemsProps;
        }

        protected function get_list($items)
        {
            $list = [];
            if (isset($items) && !empty($items)) {
                foreach ($items as $key => $item) {
                    array_push($list, $item['item']);
                }
            }

            return $list;
        }

        /* PRIVATE METHODS */
        private function is_empty($items)
        {
            $is_empty = true;

            if (!isset($items) || empty($items)) {
                return $is_empty;
            }

            // if (!is_array($items) || !is_object($items)) {
            //     return $is_empty;
            // }

            $is_pros_list_empty = (!isset($items['pros-list']) || empty($items['pros-list']));
            $is_cons_list_empty = (!isset($items['cons-list']) || empty($items['cons-list']));

            $is_pros_empty = (!isset($items['pros']) || empty($items['pros']));
            $is_cons_empty = (!isset($items['cons']) || empty($items['cons']));

            // Either should be NOT EMPTY
            if (!$is_pros_list_empty || !$is_cons_list_empty && !$is_pros_empty || !$is_cons_empty) {
                $is_empty = false;
            }

            return $is_empty;
        }

        /*
         * Below methods are not used
         */
        private function get_prosandcon_title($title = "Pros & Cons")
        {
            $condition = $this->get_condition();

            $title = ($condition['is_pros_empty']) ? 'Cons' : $title;
            $title = ($condition['is_cons_empty']) ? 'Pros' : $title;

            return $title;
        }

        private function get_condition()
        {
            $condition = [
                'is_both_empty' => false,
                'is_pros_empty' => false,
                'is_cons_empty' => false,
            ];

            if (!isset($this->items) || empty($this->items)) {
                error_log('this->items : ' . print_r($this->items, true));

                $condition['is_both_empty'] = true;
            }

            if (!isset($this->items['pros']) || empty($this->items['pros'])) {
                $condition['is_pros_empty'] = true;
            }

            if (!isset($this->items['cons']) || empty($this->items['cons'])) {
                $condition['is_cons_empty'] = true;
            }

            return $condition;
        }
    } // END CLASS
}
