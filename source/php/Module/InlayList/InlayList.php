<?php

namespace Modularity\Module\InlayList;

class InlayList extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'inlaylist',
            __("Inlay List", 'modularity'),
            __("Inlay Lists", 'modularity'),
            __("Outputs one or more posts from selected post-type.", 'modularity'),
            array(),
            null,
            null,
            3600*24*7
        );
    }
}
