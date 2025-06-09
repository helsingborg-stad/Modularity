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
        private \Municipio\StickyPost\Helper\GetStickyOption|null $getStickyOption,
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
        if(!empty($fields['posts_data_network_sources'])) {
            return $this->getPostsFromMultipleSites($fields, $page, array_map(fn($site) => $site['value'], $fields['posts_data_network_sources']));
        }

        return (array) $this->getPosts($fields, $page);
    }

    private function getPosts(array $fields, int $page):array {
        
        $stickyPostIds       = $this->getStickyPostIds($fields, $page);
        $stickyPosts         = $this->getStickyPostsForSite($fields, $page, $stickyPostIds);

        $wpQuery     = $this->wpQueryFactory->create($this->getPostArgs($fields, $page, $stickyPostIds));

        $stickyPosts = $this->sortPosts($stickyPosts, $fields['posts_sort_by'] ?? 'date', $fields['posts_sort_order'] ?? 'desc');

        $posts = $wpQuery->get_posts();

        return $this->formatResponse(
            $posts,
            $wpQuery->max_num_pages,
            $stickyPosts
        );
    }

    /**
     * Get posts from multiple sites
     * 
     * @param array $fields
     * @param int $page
     * @param array $sites
     * @return array
     */
    private function getPostsFromMultipleSites(array $fields, int $page, array $sites): array
    {
        global $wpdb;
        
        $postStatuses = is_user_logged_in() ? ['publish', 'private'] : ['publish'];
        $postStatusesSql = implode(',', array_map(fn($status) => sprintf("'%s'", esc_sql($status)), $postStatuses));
        $unionQueries = [];

        // Filter out deleted or invalid sites
        $sites = array_filter($sites, function ($site) {
            $siteObj = get_site($site);
            return $siteObj && isset($siteObj->deleted) && !$siteObj->deleted && is_a(get_blog_details($site), 'WP_Site');
        });

        if (empty($sites)) {
            return $this->formatResponse([], 0, []);
        }

        foreach ($sites as $site) {
            $postsTable = $site == 1 ? "{$wpdb->base_prefix}posts" : "{$wpdb->base_prefix}{$site}_posts";
            $postMetaTable = $site == 1 ? "{$wpdb->base_prefix}postmeta" : "{$wpdb->base_prefix}{$site}_postmeta";

            switch_to_blog($site);

            $postTypes = match ($fields['posts_data_source'] ?? null) {
                'posttype' => [$fields['posts_data_post_type']],
                'schematype' => !empty($fields['posts_data_schema_type']) ? $this->getPostTypesFromSchemaType($fields['posts_data_schema_type']) : [],
                default => ['post'],
            };

            $postTypesSql = implode(',', array_map(fn($type) => sprintf("'%s'", esc_sql($type)), $postTypes));
            restore_current_blog();

            $unionQueries[] = "
                SELECT DISTINCT
                    '{$site}' AS blog_id,
                    posts.ID AS post_id,
                    posts.post_date,
                    posts.post_title,
                    posts.post_modified,
                    posts.menu_order,
                    CASE WHEN postmeta1.meta_value COLLATE utf8mb4_swedish_ci THEN postmeta1.meta_value ELSE 0 END AS is_sticky
                FROM $postsTable posts
                LEFT JOIN $postMetaTable postmeta1 ON posts.ID = postmeta1.post_id AND postmeta1.meta_key = 'is_sticky'
                WHERE
                    posts.post_type IN ($postTypesSql)
                    AND posts.post_status IN ($postStatusesSql)
                    AND posts.post_date < NOW()
            ";
        }

        $sql = 'SELECT blog_id, post_id, post_date, is_sticky, post_title, post_modified, menu_order FROM ('
            . implode(' UNION ', $unionQueries)
            . ') as posts';

        $orderby = $fields['posts_sort_by'] ?? 'date';
        $order = strtoupper($fields['posts_sort_order'] ?? 'DESC');
        $orderSql = match ($orderby) {
            'date' => " ORDER BY post_date $order",
            'title' => " ORDER BY post_title $order",
            'modified' => " ORDER BY post_modified $order",
            'menu_order' => " ORDER BY menu_order $order",
            'rand' => " ORDER BY RAND()",
            default => " ORDER BY post_date $order",
        };
        $sql .= $orderSql;

        $dbResults = $wpdb->get_results($sql);

        $posts = [];
        if (!empty($dbResults)) {
            foreach ($dbResults as $item) {
                if (empty($item->post_id)) {
                    continue;
                }
                $post = get_blog_post($item->blog_id, $item->post_id);
                if (!$post || !in_array($post->post_status, $postStatuses, true)) {
                    continue;
                }
                $post->originalBlogId = $item->blog_id;
                $posts[] = $post;
            }
        }

        $postsPerPage = $this->getPostsPerPage($fields);
        $offset = ($page - 1) * $postsPerPage;

        return $this->formatResponse(
            array_slice($posts, $offset, $postsPerPage),
            $postsPerPage > 0 ? (int)ceil(count($posts) / $postsPerPage) : 0,
            $this->getStickyPostsForSite($fields, $page, $this->getStickyPostIds($fields, $page))
        );
    }

    /**
     * Format the response
     * 
     * @param array $posts
     * @param int $maxNumPages
     * @param array $stickyPosts
     * @return array e.g. ['posts' => [], 'maxNumPages' => 0, 'stickyPosts' => []]
     */
    private function formatResponse(array $posts, int $maxNumPages, array $stickyPosts): array
    {
        return [
            'posts' => $posts,
            'maxNumPages' => $maxNumPages,
            'stickyPosts' => $stickyPosts,
        ];
    }

    /**
     * Get sticky posts for site
     */
    private function getStickyPostsForSite(array $fields, int $page, array $stickyPostIds): array
    {
        if (
            empty($stickyPostIds) ||
            empty($fields['posts_data_source']) ||
            $fields['posts_data_source'] !== 'posttype' ||
            empty($fields['posts_data_post_type']) ||
            $page !== 1
        ) {
            return [];
        }

        if (array_key_exists($currentPostID = $this->getCurrentPostID(), $stickyPostIds)) {
            unset($stickyPostIds[$currentPostID]);
        }

        $args = $this->getDefaultQueryArgs();
        $args['post_type'] = $fields['posts_data_post_type'];
        $args['post__in'] = array_values($stickyPostIds);
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        $args['posts_per_page'] = $this->getPostsPerPage($fields);

        $args['post_status'] = ['publish', 'inherit'];
        if ($this->wpService->isUserLoggedIn()) {
            $args['post_status'][] = 'private';
        }

        $wpQuery = $this->wpQueryFactory->create($args);

        return $wpQuery->get_posts();
    }

    /**
     * Get sticky post IDs
     */
    private function getStickyPostIds(array $fields, int $page): array 
    {
        $stickyPosts = [];
        if (is_null($this->getStickyOption) || !empty($fields['posts_data_post_type'])) {
            $stickyPosts = $this->getStickyOption->getOption($fields['posts_data_post_type']);
        }

        return $stickyPosts;
    }

    /**
     * Get post args
     */
    private function getPostArgs(array $fields, int $page, array $stickyPostIds = [])
    {
        $metaQuery        = false;
        $orderby          = !empty($fields['posts_sort_by']) ? $fields['posts_sort_by'] : 'date';
        $order            = !empty($fields['posts_sort_order']) ? $fields['posts_sort_order'] : 'desc';

        // Get post args
        $getPostsArgs = $this->getDefaultQueryArgs();

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
                $postsNotIn = [];
                if ($currentPostID = $this->getCurrentPostID()) {
                    $postsNotIn[] = $currentPostID;
                }

                $postsNotIn = array_merge($postsNotIn, $stickyPostIds);
                $getPostsArgs['post__not_in'] = $postsNotIn;

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

    /**
     * Get default query args
     */
    private function getDefaultQueryArgs()
    {
        return [
            'post_type' => 'any',
            'post_password' => false,
            'suppress_filters' => false,
            'ignore_sticky_posts' => true,
        ];
    }

    /**
     * Get posts per page
     */
    private function getPostsPerPage(array $fields): int {
        if (isset($fields['posts_count']) && is_numeric($fields['posts_count'])) {
            return ($fields['posts_count'] == -1 || $fields['posts_count'] > 100) ? 100 : $fields['posts_count'];
        }

        return 10;
    }

    /**
     * Get post types from schema type
     */
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
