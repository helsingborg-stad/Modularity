<?php

namespace Modularity\Module\Video;

class Video extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'video',
            __("Video", 'modularity'),
            __("Video", 'modularity'),
            __("Outputs an embedded Video.", 'modularity'),
            array()
        );
    }
}
