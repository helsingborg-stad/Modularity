<?php

namespace Modularity\Module\Posts\Helper;

class GetPosts
{
    /**
     * Get posts and pagination data
     * 
     * @param array $fields
     * @param int $page
     * @return array $result e.g. ['posts' => [], 'maxNumPages' => 0]
     */
    public function getPostsAndPaginationData(array $fields, int $page = 1) :array
    {
        $result = (array) $this->getPostsFromSelectedSites($fields, $page);

        if( empty($result['posts'])) {
            return $result;
        }

        $result['posts'] = array_map(function($post) use ($fields) {
            $data['taxonomiesToDisplay'] = !empty($fields['taxonomy_display']) ? $fields['taxonomy_display'] : [];
            $helperClass = '\Municipio\Helper\Post';
            $helperMethod = 'preparePostObject';
            $helperArchiveMethod = 'preparePostObjectArchive';
            
            if(!class_exists($helperClass) || !method_exists($helperClass, $helperMethod) || !method_exists($helperClass, $helperArchiveMethod)) {
                error_log("Class or method does not exist: {$helperClass}::{$helperMethod} or {$helperClass}::{$helperArchiveMethod}");
                return $post;
            }

            if (in_array($fields['posts_display_as'], ['expandable-list'])) {
                $post = call_user_func([$helperClass, $helperMethod], $post);
            } else {
                $post = call_user_func([$helperClass, $helperArchiveMethod], $post, $data);
            }

            if (!empty($post->schemaData['place']['pin'])) {
                $post->attributeList['data-js-map-location'] = json_encode($post->schemaData['place']['pin']);
            }

            return $post;

        }, $result['posts']);
        
        return $result;
    }

    private function getPostsFromSelectedSites(array $fields, int $page):array {
        
        if(!empty($fields['posts_data_network_sources'])) {
            $posts      = [];
            $maxNumPages = 0;
            foreach($fields['posts_data_network_sources'] as $site) {
                switch_to_blog($site);
                $wpQuery = new \WP_Query($this->getPostArgs($fields, $page));
                $posts = array_merge($posts, $wpQuery->get_posts());
                $maxNumPages = max($maxNumPages, $wpQuery->max_num_pages);
                restore_current_blog();
            }

            return [
                'posts' => $posts,
                'maxNumPages' => $maxNumPages
            ];
        }

        $wpQuery = new \WP_Query($this->getPostArgs($fields, $page));

        return [
            'posts' => $wpQuery->get_posts(),
            'maxNumPages' => $wpQuery->max_num_pages
        ];
    }

    private function getPostArgs(array $fields, int $page)
    {
        $metaQuery  = false;
        $orderby    = !empty($fields['posts_sort_by']) ? $fields['posts_sort_by'] : 'date';
        $order      = !empty($fields['posts_sort_order']) ? $fields['posts_sort_order'] : 'desc';

        // Get post args
        $getPostsArgs = [
            'post_type' => 'any',
            'post_password' => false,
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
        if (
            isset($fields['posts_taxonomy_filter']) &&
            $fields['posts_taxonomy_filter'] === true &&
            !empty($fields['posts_taxonomy_type'])
        ) {
            $taxType = $fields['posts_taxonomy_type'];
            $taxValues = (array)$fields['posts_taxonomy_value'];

            foreach ($taxValues as $term) {
                $getPostsArgs['tax_query'][] = [
                    'taxonomy' => $taxType,
                    'field' => 'slug',
                    'terms' => $term
                ];
            }
        }

        // Meta filter
        if (isset($fields['posts_meta_filter']) && $fields['posts_meta_filter'] === true) {
            $metaQuery[] = [
                'key' => $fields['posts_meta_key'] ?? '',
                'value' => [$fields['posts_meta_value'] ?? ''],
                'compare' => 'IN',
            ];
        }

        // Data source
        switch ($fields['posts_data_source']) {
            case 'posttype':
                $getPostsArgs['post_type'] = $fields['posts_data_post_type'];
                if ($currentPostID = $this->getCurrentPostID()) {
                    $getPostsArgs['post__not_in'] = [
                        $currentPostID
                    ];
                }
                break;

            case 'children':
                $getPostsArgs['post_type'] = get_post_type();
                $getPostsArgs['post_parent'] = $fields['posts_data_child_of'];
                break;

            case 'manual':
                $getPostsArgs['post__in'] = $fields['posts_data_posts'];
                if ($orderby == 'false') {
                    $getPostsArgs['orderby'] = 'post__in';
                }
                break;

            case 'schematype':
                if(empty($fields['posts_data_schema_type'])) {
                    break;
                }
                $getPostsArgs['post_type'] = $this->getPostTypesFromSchemaType($fields['posts_data_schema_type']);
                break;
        }

        // Add metaquery to args
        if ($metaQuery) {
            $getPostsArgs['meta_query'] = $metaQuery;
        }

        // Number of posts
        if (isset($fields['posts_count']) && is_numeric($fields['posts_count'])) {
            $getPostsArgs['posts_per_page'] = $fields['posts_count'];
        }

        // Apply pagination
        $getPostsArgs['paged'] = $page;

        return $getPostsArgs;
    }

    private function getPostTypesFromSchemaType(string $schemaType):array {
        
        $class = '\Municipio\SchemaData\Helper\GetSchemaType';
        $method = 'getPostTypesFromSchemaType';
        
        if(!class_exists($class) || !method_exists($class, $method)) {
            $backtrace = debug_backtrace();
            error_log("Class or method does not exist: {$class}::{$method} in {$backtrace[0]['file']} on line {$backtrace[0]['line']}");
            return [];
        }

        $postTypes = call_user_func([new $class(), $method], $schemaType);

        if( !is_array($postTypes) ) {
            return [];
        }

        return $postTypes;
    }

    public function getCurrentPostID()
    {
        global $post;
        if (isset($post->ID) && is_numeric($post->ID)) {
            return $post->ID;
        }
        return false;
    }
}
