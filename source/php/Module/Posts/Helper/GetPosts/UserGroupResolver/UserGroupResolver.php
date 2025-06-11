<?php

namespace Modularity\Module\Posts\Helper\GetPosts\UserGroupResolver;

use WpService\Contracts\GetCurrentSite;
use WpService\Contracts\GetMainSiteId;
use WpService\Contracts\GetTerm;
use WpService\Contracts\GetUserMeta;
use WpService\Contracts\RestoreCurrentBlog;
use WpService\Contracts\SwitchToBlog;

class UserGroupResolver implements UserGroupResolverInterface
{
    public function __construct(private GetUserMeta&GetMainSiteId&SwitchToBlog&GetTerm&RestoreCurrentBlog&GetCurrentSite $wpService)
    {
    }

    /**
     * Get the user group slug for the current user.
     *
     * @return string|null The user group slug or null if not found.
     */
    public function getUserGroup(): ?string
    {
        $currentBlogId  = $this->wpService->getCurrentSite()->blog_id ?? null;
        $userGroupId    = null;

        if ($currentBlogId !== $this->wpService->getMainSiteId()) {
            $this->wpService->switchToBlog($this->wpService->getMainSiteId());
                $userGroupId = $this->getUserGroupFromBlog();
            $this->wpService->restoreCurrentBlog();
            return $userGroupId;
        }

        return $this->getUserGroupFromBlog();
    }

    /**
     * Get the user group. 
     *
     * @return string|null The user group slug or null if not found.
     */
    private function getUserGroupFromBlog():?string {
        $currentUserId  = $this->wpService->getCurrentUserId();

        if (empty($currentUserId)) {
            return null;
        }

        $userGroupId    = $this->wpService->getUserMeta($currentUserId, 'user_group', true);

        if (empty($userGroupId)) {
            return null;
        }

        $this->wpService->registerTaxonomy('user_group', 'user_group', []);

        $term = $this->wpService->getTerm($userGroupId, 'user_group');
        
        if(!is_a($term, 'WP_Term')) {
            return null;
        }
        return $term->slug;
    }
}