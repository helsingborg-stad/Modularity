<?php

namespace Modularity\Module;

class InlayList extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'inlay-list',
            __("Inlay List",'modularity-plugin'),
            __("Inlay Lists",'modularity-plugin'),
            __("Outputs one or more posts from selected post-type.",'modularity-plugin'),
            array('title')
        );
    }
}
