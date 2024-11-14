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

        $sidebarContexts = [
            'sidebar.left-sidebar',
            'sidebar.right-sidebar',
            'sidebar.left-sidebar-bottom',
            'sidebar.right-sidebar-bottom',
        ];
        if (in_array($context, $sidebarContexts)) {
            $data['compact'] = true;
        }

        return $data;
    }
}