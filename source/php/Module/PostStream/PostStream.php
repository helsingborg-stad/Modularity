<?php

namespace Modularity\Module\PostStream;

class PostStream extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'poststream',
            __("Post Stream", 'modularity-plugin'),
            __("Post Stream", 'modularity-plugin'),
            __("", 'modularity-plugin'),
            array(),
            null,
            'acf-post-type-field/acf-post-type-chooser.php' //included plugin
        );
    }
}
