<?php

namespace Modularity;

class ComponentFilters 
{
    public function __construct()
    {
        add_filter('ComponentLibrary/Component/Collection/Data', array($this, 'collectionItemCompressed'), 20);
    }

    public function collectionItemCompressed($data)
    {
        $context = \Modularity\Helper\Context::get();
        if ($context == 'sidebar.left-sidebar' || $context == 'sidebar.right-sidebar') {
            $data['compact'] = true;
        }

        return $data;
    }
}