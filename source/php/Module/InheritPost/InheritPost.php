<?php

namespace Modularity\Module\InheritPost;

class InheritPost extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'inheritpost',
            'Post article',
            'Post articles',
            'Outputs title and content from any post or page',
            array(''),
            null
        );
    }
}
