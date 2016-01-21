<?php

namespace Modularity\Module\PictureList;

class PictureList extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'picturelist',
            'Picture List',
            'Picture Lists',
            'Outputs a list of pictures',
            array()
        );
    }
}
