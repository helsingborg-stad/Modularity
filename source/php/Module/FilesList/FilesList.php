<?php

namespace Modularity\Module\FilesList;

class FilesList extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'fileslist',
            __("Files", 'modularity'),
            __("Files", 'modularity'),
            __("Outputs a file archive.", 'modularity'),
            array(),
            null,
            null,
            3600*24*7
        );
    }
}
