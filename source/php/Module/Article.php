<?php

namespace Modularity\Module;

class Article extends \Modularity\Module
{
    public function __construct()
    {
        $this->register('article', 'Article', 'Articles', 'Outputs a full article', array('title', 'editor'));
        $this->register('test', 'Gallery', 'Galleries', 'Outputs a gallery with images', array('title', 'editor'));
    }
}
