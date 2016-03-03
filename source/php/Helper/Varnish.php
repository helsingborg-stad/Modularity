<?php

namespace Modularity\Helper;

class Varnish
{
    public function __construct()
    {
        add_action('save_post', array($this, 'sendPurgeRequest'));
    }

    private function sendPurgeRequest($post_id)
    {

        //Not for revisions
        if (wp_is_post_revision($post_id)) {
            return;
        }

        //Check if modularity, then send purge!
        if ($this->isModularityPost($post_id)) {
            wp_remote_request($this->getMasterUrl(), array('method' => 'PURGE'));
            return true;
        }

        return false;
    }

    private function isModularityPost($post_id)
    {
        if (strpos(get_post_type($post_id), "mod-") === 0) {
            return true;
        }
        return false;
    }

    private function getMasterUrl()
    {
        if (!is_multisite()) {
            return home_url();
        } else {
            return get_site_url(get_current_blog_id());
        }
    }
}
