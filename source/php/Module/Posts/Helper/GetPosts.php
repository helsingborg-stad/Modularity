<?php

namespace Modularity\Module\Posts\Helper;

class GetPosts
{
    public static function getPosts($fields) {
        $posts = (array) get_posts(self::getPostArgs($fields));
        if (!empty($posts)) {
            foreach ($posts as &$_post) {
                $data['taxonomiesToDisplay'] = !empty($fields->taxonomy_display) ? $fields->taxonomy_display : [];

               if (class_exists('\Municipio\Helper\Post')) {
                    if (in_array($fields->posts_display_as, [ 'expandable-list'])) {
                        $_post = \Municipio\Helper\Post::preparePostObject($_post);
                    } else {
                        $_post = \Municipio\Helper\Post::preparePostObjectArchive($_post, $data);
                    }
                
                    if (!empty($_post)) {
                        $_post->attributeList['data-js-map-location'] = json_encode($_post->location);
                    }
                } 
            }
            return $posts;
        }
        return [];
    }

    private static function getPostArgs($fields)
    {
        $metaQuery  = false;
        $orderby    = !empty($fields->posts_sort_by) ? $fields->posts_sort_by : 'date';
        $order      = !empty($fields->posts_sort_order) ? $fields->posts_sort_order : 'desc';

        // Get post args
        $getPostsArgs = [
            'post_type' => 'any',
            'suppress_filters' => false
        ];

        // Sort by meta key
        if (strpos($orderby, '_metakey_') === 0) {
            $orderby_key = substr($orderby, strlen('_metakey_'));
            $orderby = 'order_clause';
            $metaQuery = [
                [
                    'relation' => 'OR',
                    'order_clause' => [
                        'key' => $orderby_key,
                        'compare' => 'EXISTS'
                    ],
                    [
                        'key' => $orderby_key,
                        'compare' => 'NOT EXISTS'
                    ]
                ]
            ];
        }

        if ($orderby != 'false') {
            $getPostsArgs['order'] = $order;
            $getPostsArgs['orderby'] = $orderby;
        }

        // Post statuses
        $getPostsArgs['post_status'] = ['publish', 'inherit'];
        if (is_user_logged_in()) {
            $getPostsArgs['post_status'][] = 'private';
        }

        // Taxonomy filter
        if (isset($fields->posts_taxonomy_filter) && $fields->posts_taxonomy_filter === true && !empty($fields->posts_taxonomy_type)) {
            $taxType = $fields->posts_taxonomy_type;
            $taxValues = (array)$fields->posts_taxonomy_value;

            foreach ($taxValues as $term) {
                $getPostsArgs['tax_query'][] = [
                    'taxonomy' => $taxType,
                    'field' => 'slug',
                    'terms' => $term
                ];
            }
        }

        // Meta filter
        if (isset($fields->posts_meta_filter) && $fields->posts_meta_filter === true) {
            $metaQuery[] = [
                'key' => $fields->posts_meta_key ?? '',
                'value' => [$fields->posts_meta_value ?? ''],
                'compare' => 'IN',
            ];
        }

        // Data source
        switch ($fields->posts_data_source) {
            case 'posttype':
                $getPostsArgs['post_type'] = $fields->posts_data_post_type;
                if($currentPostID = self::getCurrentPostID()) {
                    $getPostsArgs['post__not_in'] = [
                        $currentPostID
                    ]; 
                }
                break;

            case 'children':
                $getPostsArgs['post_type'] = get_post_type();
                $getPostsArgs['post_parent'] = $fields->posts_data_child_of;
                break;

            case 'manual':
                $getPostsArgs['post__in'] = $fields->posts_data_posts;
                if ($orderby == 'false') {
                    $getPostsArgs['orderby'] = 'post__in';
                }
                break;
        }

        // Add metaquery to args
        if ($metaQuery) {
            $getPostsArgs['meta_query'] = $metaQuery;
        }

        //Number of posts
        if(isset($fields->posts_count) && is_numeric($fields->posts_count)) {
            $getPostsArgs['posts_per_page'] = $fields->posts_count; 
        }

        return $getPostsArgs;
    }

    private static function getCurrentPostID() {
        global $post; 
        if(isset($post->ID) && is_numeric($post->ID)) {
            return $post->ID;
        }
        return false;
    }
}
