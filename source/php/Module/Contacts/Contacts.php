<?php

namespace Modularity\Module\Contacts;

class Contacts extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'contacts',
            __('Contacts v2', 'modularity'),
            __('Contacts v2', 'modularity'),
            __('Outputs one or more contacts', 'modularity'),
            array(),
            null,
            null,
            3600*24*7,
            true
        );
    }
}
