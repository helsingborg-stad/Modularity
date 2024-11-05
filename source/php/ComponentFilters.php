<?php

namespace Modularity;

class ComponentFilters 
{
    public function __construct()
    {
        add_filter('ComponentLibrary/Component/Collection/Compact', array($this, 'collectionItemCompressed'), 20);
    }

    public function collectionItemCompressed($compact)
    {
        $context = \Modularity\Helper\Context::get();
        if ($context == 'sidebar.left-sidebar' || $context == 'sidebar.right-sidebar') {
            $compact = true;
        }

        return $compact;
    }
}