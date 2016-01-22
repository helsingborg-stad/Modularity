<?php

namespace Modularity\Module\Contact;

class Contact extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'contact',
            'Contact',
            'Contacts',
            'Outputs one or more contacts',
            array('editor')
        );
    }
}
