<?php

namespace Modularity\Module\Script;

class Script extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'script',
            __("Script", 'modularity'),
            __("Script", 'modularity'),
            __("Outputs unsanitized code to widget area.", 'modularity'),
            array(),
            null,
            null,
            3600*24*7
        );
    }
}
