<?php

namespace Modularity\Module\Posts\Private;

use Modularity\Module\Posts\Posts;

class PrivateController {
    public function __construct(private Posts $postsInstance)
    {
        $this->registerMeta();

        if ($this->postsInstance->postStatus === 'private') {
            $this->postsInstance->cacheTtl = 0;
            add_filter(
                'Modularity/Module/Posts/template', 
                array($this, 'checkIfModuleCanBeEditedByUser'), 
                999, 
                4
            );
        }
    }

    public function checkIfModuleCanBeEditedByUser($view, $instance, $data, $fields)
    {
        if (!$this->allowsUserModification($fields)) {
            return $view;
        }

        return 'private.blade.php';
    }

    public function decorateData(array $data, array $fields): array
    {
        if (!$this->allowsUserModification($fields)) {
            return $data;
        }
        
        $user = wp_get_current_user();

        $data['currentUser']      = $user->ID;
        $data['moduleId']         = $this->postsInstance->ID;
        $data['userCanEditPosts'] = true;
        $data['filteredPosts']    = $this->getUserStructuredPosts($data['posts'], $data['currentUser']);

        return $data;
    }

    private function getUserStructuredPosts(array $posts, int $currentUser): array
    {
        $moduleId = $this->postsInstance->ID;
        $userPosts = get_user_meta($currentUser, 'privatePostsModule', true);

        if (empty($userPosts) || empty($userPosts[$moduleId])) {
            return $posts;
        }

        $userPostIds = $userPosts[$moduleId];

        foreach ($posts as &$post) {
            $post->classList = $post->classList ?? [];

            if (
                isset($userPostIds[$post->getId()]) && 
                empty($userPostIds[$post->getId()])
            ) {
                $post->checked     = false;
                $post->classList[] = 'u-display--none';
            } else {
                $post->checked     = true;
            }
        }

        return $posts;
    }

    private function allowsUserModification(array $fields): bool
    {
        return 
            $this->postsInstance->postStatus === 'private' && 
            !empty($fields['allow_user_modification']) && 
            is_user_logged_in();
    }

    private function registerMeta(): void
    {
        register_meta('user', 'privatePostsModule', array(
            'type' => 'object',
            'show_in_rest' => array(
                'schema' => array(
                    'type' => 'object',
                    'additionalProperties' => array(
                        'type' => 'object',
                        'properties' => array(
                            'key' => array(
                                'type' => 'bool',
                            ),
                        ),
                        'additionalProperties' => true,
                    ),
                ),
            ),
            'single' => true,
        ));
    }
}