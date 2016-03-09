<?php

namespace Modularity\Module\Social;

class Social extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'social',
            __("Social Media Feed", 'modularity-plugin'),
            __("Sociala Media Feeds", 'modularity-plugin'),
            __("Outputs a social media feed from desired username or hashtag (facebook, instagram, twitter, linkedin).", 'modularity-plugin'),
            array()
        );
    }

}
