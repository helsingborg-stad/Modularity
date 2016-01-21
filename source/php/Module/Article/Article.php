<?php

namespace Modularity\Module\Article;

class Article extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'article',
            'Article',
            'Articles',
            'Outputs a full article',
            array('editor')
        );
    }
}
