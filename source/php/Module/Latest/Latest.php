<?php

namespace Modularity\Module\Latest;

class Latest extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'latest',
            'Latest',
            'Latest',
            'Outputs the latest posts in a given order',
            array('')
        );
    }
}
