<?php

namespace Modularity\Module\Posts\Helper;

use Modularity\Helper\WpQueryFactory\WpQueryFactoryInterface;
use WpService\Contracts\{
    GetPermalink,
    GetPostType,
    GetTheID,
    IsArchive,
    IsUserLoggedIn,
    RestoreCurrentBlog,
    SwitchToBlog,
};

class GetPosts
{
    public function __construct(
        private IsUserLoggedIn&SwitchToBlog&RestoreCurrentBlog&GetPermalink&GetPostType&IsArchive&GetTheID $wpService,
        private WpQueryFactoryInterface $wpQueryFactory
    )
    {
    }

    /**
     * Get posts and pagination data
     * 
     * @param array $fields
     * @param int $page
     * @return array $result e.g. ['posts' => [], 'maxNumPages' => 0]
     */
    public function getPostsAndPaginationData(array $fields, int $page = 1) :array
    {
        return (array) $this->getPostsFromSelectedSites($fields, $page);
    }

    private function getPostsFromSelectedSites(array $fields, int $page):array {
        
        if(!empty($fields['posts_data_network_sources'])) {
            $posts      = [];
            $maxNumPages = 0;

            foreach($fields['posts_data_network_sources'] as $site) {
                $this->wpService->switchToBlog($site['value']);
                $wpQuery = new \WP_Query($this->getPostArgs($fields, $page));
                $postsFromSite = $wpQuery->get_posts();

                array_walk($postsFromSite, function($post) use ($site) {
                    // Add the original permalink to the post object for reference in network sources.
                    $post->originalPermalink = $this->wpService->getPermalink($post->ID);
                    $post->originalSite      = $site['label'];
                    $post->originalBlogId    = (int)$site['value'];
                });

                $posts = array_merge($posts, $postsFromSite);
                $maxNumPages = max($maxNumPages, $wpQuery->max_num_pages);
                $this->wpService->restoreCurrentBlog();
            }

            // Limit the number of posts to the desired count to avoid exceeding the limit.
            $posts = $this->sortPosts($posts, $fields['posts_sort_by'] ?? 'date', $fields['posts_sort_order'] ?? 'desc');
            $posts = array_slice($posts, 0, $this->getPostsPerPage($fields));

            return [
                'posts' => $posts,
                'maxNumPages' => $maxNumPages
            ];
        }

        $wpQuery = $this->wpQueryFactory->create($this->getPostArgs($fields, $page));

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
        if ($this->wpService->isUserLoggedIn()) {
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
        switch ($fields['posts_data_source'] ?? []) {
            case 'posttype':
                $getPostsArgs['post_type'] = $fields['posts_data_post_type'];
                if ($currentPostID = $this->getCurrentPostID()) {
                    $getPostsArgs['post__not_in'] = [
                        $currentPostID
                    ];
                }
                break;

            case 'children':
                $getPostsArgs['post_type'] = $this->wpService->getPostType();
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
        $getPostsArgs['posts_per_page'] = $this->getPostsPerPage($fields);

        // Apply pagination
        $getPostsArgs['paged'] = $page;

        return $getPostsArgs;
    }

    private function getPostsPerPage(array $fields): int {
        if (isset($fields['posts_count']) && is_numeric($fields['posts_count'])) {
            return ($fields['posts_count'] == -1 || $fields['posts_count'] > 100) ? 100 : $fields['posts_count'];
        }

        return 10;
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

    /**
     * Get the current post ID
     */
    public function getCurrentPostID():int|false
    {
        if ($this->wpService->isArchive()) {
            return false;
        }

        return $this->wpService->getTheID();
    }

    /**
     * Sort posts
     * 
     * @param \WP_Post[] $posts
     * @param string $orderby Can be 'date', 'title', 'modified', 'menu_order', 'rand'. Default is 'date'.
     * @param string $order Can be 'asc' or 'desc'. Default is 'desc'. When 'rand' is used, this parameter is ignored.
     */
    public function sortPosts(array $posts, string $orderby = 'date', string $order = 'desc') : array
    {
        usort($posts, fn($a, $b) =>
            match($orderby) {
                'date' => strtotime($a->post_date) > strtotime($b->post_date) ? ($order == 'asc' ? 1 : -1) : ($order == 'asc' ? -1 : 1),
                'title' => $a->post_title > $b->post_title ? ($order == 'asc' ? 1 : -1) : ($order == 'asc' ? -1 : 1),
                'modified' => strtotime($a->post_modified) > strtotime($b->post_modified) ? ($order == 'asc' ? 1 : -1) : ($order == 'asc' ? -1 : 1),
                'menu_order' => $a->menu_order > $b->menu_order ? ($order == 'asc' ? 1 : -1) : ($order == 'asc' ? -1 : 1),
                'rand' => rand(-1, 1),
                default => 0,
            }
        );

        return $posts;
    }
}
