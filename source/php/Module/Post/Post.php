<?php

namespace Modularity\Module\Post;

class Post extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'inheritpost',
            'Post article',
            'Post articles',
            'Outputs title and content from any post or page',
            array('')
        );
    }
}
