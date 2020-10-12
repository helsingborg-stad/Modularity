<?php

namespace Modularity\Module\MainNews;

class MainNews extends \Modularity\Module
{
    public $isDeprecated = true;

    public function __construct()
    {
        $this->register(
            'mainnews',
            __('Main News', 'modularity'),
            __('Main News', 'modularity'),
            'Outputs a list of prioritized articles',
            array(),
            null,
            null,
            3600*24*7,
            true
        );
    }
}
