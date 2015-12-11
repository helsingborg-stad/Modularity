<?php

namespace Modularity\Module\Iframe;

class Iframe extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'iframe',
            __("Iframe (embed)", 'modularity-plugin'),
            __("Iframes", 'modularity-plugin'),
            __("Outputs an embedded page inside a div.", 'modularity-plugin'),
            array()
        );

        add_action('plugins_loaded', array($this,'acfFields'));
    }

    public function acfFields()
    {
	    
    }
}
