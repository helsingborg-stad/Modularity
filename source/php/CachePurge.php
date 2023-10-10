<?php

namespace Modularity;
/**
 * Class CachePurge
 *
 * The CachePurge class provides functionality for purging cache related to modularity posts.
 * 
 * TODO: Narrow the purge scope, to pages containing the relevant module.
 *       May be done with \Modularity\ModuleManager::getModuleUsage($post->ID);
 *
 * @package Modularity
 */

class CachePurge
{
    public function __construct()
    {
        add_action('save_post', array($this, 'sendPurgeRequest'));
    }

    /**
     * Send a purge request for a post if it's a modularity type.
     *
     * This function checks if a post with the given post ID is a modularity type. If it is, it sends
     * a purge request to the master URL. Purge requests are not sent for post revisions.
     *
     * @param int $post_id The ID of the post to check and potentially send a purge request for.
     * @return bool True if a purge request was sent, false otherwise (including revisions).
     */
    public function sendPurgeRequest($postId)
    {

        //Not for revisions
        if (wp_is_post_revision($postId)) {
            return;
        }

        //Check if modularity, then send purge!
        if ($this->isModularityPost($postId)) {
            wp_remote_request($this->getMasterUrl(),
                array(
                    'method' => 'PURGE',
                    'timeout' => 2,
                    'redirection' => 0,
                    'blocking' => false
                )
            );
            return true;
        }

        return false;
    }

    /**
     * Check if a post is of a modularity type.
     *
     * This function determines if a post, identified by its post ID, belongs to a modularity
     * type. Modularity types are identified by post types starting with "mod-".
     *
     * @param int $postId The ID of the post to check.
     * @return bool True if the post is of a modularity type, false otherwise.
     */
    private function isModularityPost($postId)
    {
        if (strpos(get_post_type($postId), "mod-") === 0) {
            return true;
        }
        return false;
    }

    /**
     * Get the master URL for the website.
     *
     * If the website is not a multisite installation, it returns the home URL.
     * If the website is part of a multisite network, it returns the site URL of the current blog.
     *
     * @return string The master URL of the website.
     */
    private function getMasterUrl()
    {
        if (!is_multisite()) {
            return home_url();
        }
        return get_site_url(get_current_blog_id());
    }
}
