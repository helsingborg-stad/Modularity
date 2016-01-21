<?php

namespace Modularity\Module\Form;

class Form extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'form',
            __("Form",'modularity-plugin'),
            __("Forms",'modularity-plugin'),
            __("Outputs a flexible form with editable input fields. Saving to a post-type with optional email to reciver.",'modularity-plugin'),
            array()
        );
    }
}
