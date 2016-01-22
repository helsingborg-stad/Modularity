<?php

namespace Modularity\Module\MainNews;

class MainNews extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'mainnews',
            'Main News',
            'Main News',
            'Outputs a list of prioritized articles',
            array()
        );
    }
}
