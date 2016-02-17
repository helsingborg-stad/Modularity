<?php

namespace Modularity\Module\Script;

class Script extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'script',
            __("Script", 'modularity-plugin'),
            __("Script", 'modularity-plugin'),
            __("Outputs unsanitized code to widget area.", 'modularity-plugin'),
            array()
        );
    }
}
