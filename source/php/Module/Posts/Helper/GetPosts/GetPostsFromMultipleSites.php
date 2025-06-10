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
    IsUserLoggedIn,
    RestoreCurrentBlog,
    SwitchToBlog,
};

class GetPostsFromMultipleSites implements GetPostsInterface
{
    public function __construct(
        private array $fields,
        private int $page,
        private array $siteIds,
        private \wpdb $wpdb,
        private IsUserLoggedIn&EscSql&GetBlogDetails&SwitchToBlog&RestoreCurrentBlog&GetBlogPost $wpService,
        private PostTypesFromSchemaTypeResolverInterface $postTypesFromSchemaTypeResolver
    ) {}

    public function getSql(): string {
        return $this->buildUnionSql(array_map(
            fn($site) => $this->buildSiteQuery($site, $this->toSqlList($this->getPostStatuses())),
            $this->getValidSites()
        ));
    }

    public function getPosts(): PostsResultInterface
    {
        $currentBlogId = $this->wpService->getBlogDetails()->blog_id;
        $postStatuses = $this->getPostStatuses();
        $sites = $this->getValidSites();
        
        if (empty($sites)) {
            return $this->formatResponse([], 0, []);
        }
        
        $dbResults = $this->wpdb->get_results($this->getSql());

        $postsPerPage = $this->getPostsPerPage();
        $offset = ($this->page - 1) * $postsPerPage;
        $maxNumPages = $postsPerPage > 0 ? (int)ceil(count($dbResults) / $postsPerPage) : 0;
        $pagedResults = array_slice($dbResults, $offset, $postsPerPage);

        $posts = $this->fetchPosts($pagedResults, $postStatuses, $currentBlogId);

        return $this->formatResponse($posts, $maxNumPages, []);
    }

    private function getPostStatuses(): array
    {
        return $this->wpService->isUserLoggedIn() ? ['publish', 'private'] : ['publish'];
    }

    private function toSqlList(array $items): string
    {
        return implode(',', array_map(
            fn($item) => sprintf("'%s'", $this->wpService->escSql($item)),
            $items
        ));
    }

    private function getValidSites(): array
    {
        $numericSiteIds = array_filter($this->siteIds, 'is_numeric');
        return array_map('intval', $numericSiteIds);   
    }

    private function buildSiteQuery($site, $postStatusesSql): string
    {
        $postsTable = $site == 1 ? "{$this->wpdb->base_prefix}posts" : "{$this->wpdb->base_prefix}{$site}_posts";
        $postMetaTable = $site == 1 ? "{$this->wpdb->base_prefix}postmeta" : "{$this->wpdb->base_prefix}{$site}_postmeta";

        $this->wpService->switchToBlog($site);
        $postTypes = $this->resolvePostTypes();
        $postTypesSql = $this->toSqlList($postTypes);
        $this->wpService->restoreCurrentBlog();

        return "
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

    private function resolvePostTypes(): array
    {
        return match ($this->fields['posts_data_source'] ?? null) {
            'posttype' => [$this->fields['posts_data_post_type']],
            'schematype' => !empty($this->fields['posts_data_schema_type'])
                ? $this->postTypesFromSchemaTypeResolver->resolve($this->fields['posts_data_schema_type'])
                : [],
            default => ['post'],
        };
    }

    private function buildUnionSql(array $unionQueries): string
    {
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
        return $sql . $orderSql;
    }

    private function fetchPosts(array $dbResults, array $postStatuses, int $currentBlogId): array
    {
        $dbResults = array_filter($dbResults, fn($item) => !empty($item->post_id));

        $posts = array_map(function ($item) use ($postStatuses, $currentBlogId) {
            $post = $this->wpService->getBlogPost($item->blog_id, $item->post_id);
            if (!$post || !in_array($post->post_status, $postStatuses, true)) {
                return null;
            }
            if ($item->blog_id !== $currentBlogId) {
                $post->originalBlogId = $item->blog_id;
            }
            return $post;
        }, $dbResults);

        return array_values(array_filter($posts));
    }

    private function formatResponse(array $posts, int $maxNumPages, array $stickyPosts): PostsResultInterface
    {
        return new PostsResult($posts, $maxNumPages, $stickyPosts);
    }

    private function getPostsPerPage(): int
    {
        if (isset($this->fields['posts_count']) && is_numeric($this->fields['posts_count'])) {
            $count = (int)$this->fields['posts_count'];
            return ($count == -1 || $count > 100) ? 100 : $count;
        }
        return 10;
    }
}
