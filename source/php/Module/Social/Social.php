<?php

namespace Modularity\Module\Social;

class Social extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'social',
            __("Social Media Feed", 'modularity'),
            __("Sociala Media Feeds", 'modularity'),
            __("Outputs a social media feed from desired username or hashtag (facebook, instagram, twitter, linkedin).", 'modularity'),
            array(),
            null,
            null,
            0,
            true
        );
    }

}
