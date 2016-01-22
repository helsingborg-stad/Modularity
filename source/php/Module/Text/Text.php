<?php

namespace Modularity\Module\Text;

class Text extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'text',
            'Text',
            'Texts',
            'Outputs a text',
            array('editor')
        );
    }
}
