<?php

namespace Modularity\Module\Contact;

class Contact extends \Modularity\Module
{
    public $isDeprecated = true;

    public function __construct()
    {
        $this->register(
            'contact',
            __('Contact', 'modularity'),
            __('Contacts', 'modularity'),
            __('Outputs one or more contacts', 'modularity'),
            array(),
            null,
            null,
            3600*24*7,
            true
        );
    }
}
