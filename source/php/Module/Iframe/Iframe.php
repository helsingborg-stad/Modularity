<?php

namespace Modularity\Module\Iframe;

class Iframe extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'iframe',
            __("Iframe", 'modularity-plugin'),
            __("Iframe", 'modularity-plugin'),
            __("Outputs an embedded page.", 'modularity-plugin'),
            array()
        );
    }
}
