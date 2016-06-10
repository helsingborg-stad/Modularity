<?php

namespace Modularity\Module\Iframe;

class Iframe extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'iframe',
            __("Iframe", 'modularity'),
            __("Iframe", 'modularity'),
            __("Outputs an embedded page.", 'modularity'),
            array()
        );
    }
}
