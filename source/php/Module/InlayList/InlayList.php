<?php

namespace Modularity\Module\InlayList;

class InlayList extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'inlaylist',
            __("Inlay List",'modularity-plugin'),
            __("Inlay Lists",'modularity-plugin'),
            __("Outputs one or more posts from selected post-type.",'modularity-plugin'),
            array()
        );
    }
}
