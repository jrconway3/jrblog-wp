<?php

namespace JR\Models;

use \JR\Models\Review;

class Reviews {
    
    /**
     * Filters person post type entries
     * 
     * @param  array  $filters Associative array of filters
     * @return array           Array of \TR\Models\Review objects
     */
    public static function filter(array $filters)
    {
        $args = array(
            'numberposts' => -1,
            'order'       => 'DESC',
            'orderby'     => 'post_date',
            'post_type'   => 'review'
        );

        if (isset($filters['numberposts'])) {
            $args['numberposts'] = $filters['numberposts'];
        }

        if (isset($filters['orderby'])) {
            $args['orderby'] = $filters['orderby'];
        }

        if (isset($filters['order'])) {
            $args['order'] = $filters['order'];
        }


        if (isset($filters['is_shared'])) {

            if(empty($args['meta_query']) || is_array($args['meta_query'])) {
                $args['meta_query'] = array();
            }
            $args['meta_query'][] = array(
                'key'     => 'jrblog_review_sharing',
                'value'   => $filters['is_shared'],
                'compare' => '=',
                'type'    => 'BINARY'
            );
        }


        if (isset($filters['is_follow'])) {

            if(empty($args['meta_query']) || is_array($args['meta_query'])) {
                $args['meta_query'] = array();
            }
            $args['meta_query'][] = array(
                'key'     => 'jrblog_review_follow',
                'value'   => $filters['is_follow'],
                'compare' => '=',
                'type'    => 'BINARY'
            );
        }


        if (isset($filters['game_id'])) {

            if(empty($args['meta_query']) || is_array($args['meta_query'])) {
                $args['meta_query'] = array();
            }
            $args['meta_query'][] = array(
                'key'     => 'associated_games',
                'value'   => serialize(strval($filters['game_id'])),
                'compare' => 'LIKE'
            );
        }

        if (isset($filters['character_id'])) {

            if(empty($args['meta_query']) || is_array($args['meta_query'])) {
                $args['meta_query'] = array();
            }
            $args['meta_query'][] = array(
                'key'     => 'associated_characters',
                'value'   => serialize(strval($filters['character_id'])),
                'compare' => 'LIKE'
            );
        }


        $items = get_posts($args);

        $parsed_items = array();
        if ($items) 
        {
            foreach ($items as $item) 
            {
                $parsed_items[] = new Review($item);
            }
        }

        return $parsed_items;
    }
}

