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
            array()
        );
    }
}
