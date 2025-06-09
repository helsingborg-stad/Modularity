<?php

namespace Modularity\Module\Posts\Helper\GetPosts;

use Modularity\Module\Posts\Helper\GetPosts\GetPostsInterface;
use Modularity\Module\Posts\Helper\GetPosts\PostsResult;
use Modularity\Module\Posts\Helper\GetPosts\PostsResultInterface;
use Modularity\Module\Posts\Helper\GetPosts\PostTypesFromSchemaType\PostTypesFromSchemaTypeResolverInterface;
use WpService\Contracts\{
    EscSql,
    GetBlogDetails,
    GetBlogPost,
    GetSite,
    IsUserLoggedIn,
    RestoreCurrentBlog,
    SwitchToBlog,
};

class GetPostsFromMultipleSites implements GetPostsInterface
{
    /**
     * Constructor
     * 
     * @param array $fields
     * @param int $page
     * @param array $siteIds
     * @param IsUserLoggedIn&EscSql&GetSite&GetBlogDetails&SwitchToBlog&RestoreCurrentBlog&GetBlogPost $wpService
     */
    public function __construct(
        private array $fields,
        private int $page,
        private array $siteIds,
        private IsUserLoggedIn&EscSql&GetSite&GetBlogDetails&SwitchToBlog&RestoreCurrentBlog&GetBlogPost $wpService,
        private PostTypesFromSchemaTypeResolverInterface $postTypesFromSchemaTypeResolver
    )
    {
    }

    /**
     * Get posts and pagination data
     */
    public function getPosts() :PostsResultInterface
    {
        global $wpdb;
        
        $currentBlogId = $this->wpService->getBlogDetails()->blog_id;
        $postStatuses = $this->wpService->isUserLoggedIn() ? ['publish', 'private'] : ['publish'];
        $postStatusesSql = implode(',', array_map(fn($status) => sprintf("'%s'", $this->wpService->escSql($status)), $postStatuses));
        $unionQueries = [];

        // Filter out deleted or invalid sites
        $sites = array_filter($this->siteIds, function ($site) {
            $siteObj = $this->wpService->getSite($site);
            return $siteObj && isset($siteObj->deleted) && !$siteObj->deleted && is_a($this->wpService->getBlogDetails($site), 'WP_Site');
        });

        if (empty($sites)) {
            return $this->formatResponse([], 0, []);
        }

        foreach ($sites as $site) {
            $postsTable = $site == 1 ? "{$wpdb->base_prefix}posts" : "{$wpdb->base_prefix}{$site}_posts";
            $postMetaTable = $site == 1 ? "{$wpdb->base_prefix}postmeta" : "{$wpdb->base_prefix}{$site}_postmeta";

            $this->wpService->switchToBlog($site);

            $postTypes = match ($this->fields['posts_data_source'] ?? null) {
                'posttype' => [$this->fields['posts_data_post_type']],
                'schematype' => !empty($this->fields['posts_data_schema_type']) ? $this->postTypesFromSchemaTypeResolver->resolve($this->fields['posts_data_schema_type']) : [],
                default => ['post'],
            };

            $postTypesSql = implode(',', array_map(fn($type) => sprintf("'%s'", $this->wpService->escSql($type)), $postTypes));
            $this->wpService->restoreCurrentBlog();

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

        $orderby = $this->fields['posts_sort_by'] ?? 'date';
        $order = strtoupper($this->fields['posts_sort_order'] ?? 'DESC');
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
        $postsPerPage = $this->getPostsPerPage();
        $offset = ($this->page - 1) * $postsPerPage;
        $maxNumPages = $postsPerPage > 0 ? (int)ceil(count($dbResults) / $postsPerPage) : 0;
        $dbResults = array_slice($dbResults, $offset, $postsPerPage);

        $dbResults = array_filter($dbResults, function ($item) {
            return !empty($item->post_id);
        });

        $posts = array_map(function($item) use ($postStatuses, $currentBlogId) {
            $post = $this->wpService->getBlogPost($item->blog_id, $item->post_id);
            if (!$post || !in_array($post->post_status, $postStatuses, true)) {
                return null;
            }
            
            if($item->blog_id !== $currentBlogId) {
                $post->originalBlogId = $item->blog_id;
            }

            return $post;
        }, $dbResults);

        return $this->formatResponse(
            array_values(array_filter($posts)),
            $maxNumPages,
            []
        );
    }

    /**
     * Format the response
     * 
     * @param array $posts
     * @param int $maxNumPages
     * @param array $stickyPosts
     * @return PostsResultInterface
     */
    private function formatResponse(array $posts, int $maxNumPages, array $stickyPosts): PostsResultInterface
    {
        return new PostsResult( $posts, $maxNumPages, $stickyPosts );
    }

    /**
     * Get posts per page
     */
    private function getPostsPerPage(): int {
        if (isset($this->fields['posts_count']) && is_numeric($this->fields['posts_count'])) {
            return ($this->fields['posts_count'] == -1 || $this->fields['posts_count'] > 100) ? 100 : $this->fields['posts_count'];
        }

        return 10;
    }
}
