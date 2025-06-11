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
    private const TAXONOMY_NAME = 'user_group';

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
        
        // Current user ID
        $currentUserId  = $this->wpService->getCurrentUserId();
        if (empty($currentUserId)) {
            return null;
        }

        // User group ID from
        $userGroupId = $this->wpService->getUserMeta($currentUserId, 'user_group', true);
        if (empty($userGroupId)) {
            return null;
        }

        // Register the taxonomy if it does not exist
        $this->maybeRegisterTaxonomy();

        // Get the term for the user group ID
        $term = $this->wpService->getTerm($userGroupId, self::TAXONOMY_NAME);

        // If the term is not found or is not a valid WP_Term, return null
        return (!is_a($term, 'WP_Term')) ? null : $term->slug;
    }

    /**
     * Register the user group taxonomy if it does not exist.
     */
    private function maybeRegisterTaxonomy(): void
    {
        if (!$this->wpService->taxonomyExists(self::TAXONOMY_NAME)) {
            $this->wpService->registerTaxonomy(self::TAXONOMY_NAME, self::TAXONOMY_NAME, []);
        }
    }
}