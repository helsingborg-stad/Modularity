<?php

namespace Modularity;

use Modularity\Module\Posts\Helper\GetPosts;
use Municipio\Api\Posts\MunicipioPostsResolverInterface;
use WP_REST_Request;

class PostResolver implements MunicipioPostsResolverInterface
{
    public function __construct(
        private array $viewPaths, 
        private string $identifier,
        private GetPosts $getPosts
    )
    {
    }

    public function resolve(WP_REST_Request $request): ?array {
        $params = $request->get_params();
        $moduleId = $params['module-id'] ? (int) $params['module-id'] : null;
        $fields = get_fields($moduleId) ?: [];
        
        $posts = $this->getPosts->getPostsAndPaginationData($fields, 1);
        echo '<pre>' . print_r( $posts, true ) . '</pre>';die;
        return null;
    }

    public function canResolveRequest(WP_REST_Request $request): bool {
        $identifier = $request->get_param('identifier');

        if (!empty($identifier) && $identifier === $this->identifier) {
            return true;
        }

        return false;
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }

    public function getViewPaths(): array {
        return $this->viewPaths;
    }
}