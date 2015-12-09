<?php

namespace Modularity\Module\Hero;

class Hero extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'hero',
            __("Hero (slider)", 'modularity-plugin'),
            __("Heroes (sliders)", 'modularity-plugin'),
            __("Outputs multiple images or videos in a sliding apperance.", 'modularity-plugin'),
            array(),
            null,
            'acf-website-field/acf-website_field.php'
        );

        add_action('plugins_loaded', array($this,'acfFields'));
    }

    public function acfFields()
    {

    }
}
