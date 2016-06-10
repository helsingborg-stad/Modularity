<?php

namespace Modularity\Module\Notice;

class Notice extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'notice',
            __("Notice", 'modularity'),
            __("Notice", 'modularity'),
            __("Outputs a notice", 'modularity'),
            array()
        );
    }
}
