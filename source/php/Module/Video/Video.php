<?php

namespace Modularity\Module\Video;

class Video extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'video',
            __("Video", 'modularity-plugin'),
            __("Video", 'modularity-plugin'),
            __("Outputs an embedded Video.", 'modularity-plugin'),
            array()
        );
    }
}
