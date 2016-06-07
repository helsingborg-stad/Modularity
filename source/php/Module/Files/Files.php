<?php

namespace Modularity\Module\Files;

class Files extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'files',
            __("Files", 'modularity-plugin'),
            __("Files", 'modularity-plugin'),
            __("Outputs a file archive.", 'modularity-plugin'),
            array()
        );
    }
}
