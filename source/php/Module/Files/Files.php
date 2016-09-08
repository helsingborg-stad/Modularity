<?php

namespace Modularity\Module\Files;

class Files extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'files',
            __("Files", 'modularity'),
            __("Files", 'modularity'),
            __("Outputs a file archive.", 'modularity'),
            array(),
            null,
            null,
            true,
            3600*24*7
        );
    }
}
