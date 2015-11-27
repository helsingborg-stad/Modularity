<?php

namespace Modularity;

class Editor
{
    public function __construct()
    {
        $tabs = new \Modularity\Editor\Tabs();
        $tabs->add(__('Content editor'), 'admin.php');
        $tabs->add(__('Module editor'), 'layout.php');
    }
}
