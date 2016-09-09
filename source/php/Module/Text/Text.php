<?php

namespace Modularity\Module\Text;

class Text extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'text',
            __('Text', 'modularity'),
            __('Texts', 'modularity'),
            'Outputs a text',
            array('editor'),
            null,
            null,
            true,
            3600*24*7
        );
    }
}
