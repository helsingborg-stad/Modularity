<?php

namespace Modularity\Module;

class Gallery extends \Modularity\Module
{
    public function __construct()
    {
       	$this->register(
            'gallery',
            'Gallery',
            'Galleries',
            'Outputs a gallery with images',
            array('title')
        );
    }
}
