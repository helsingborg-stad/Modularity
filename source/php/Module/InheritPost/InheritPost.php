<?php

namespace Modularity\Module\InheritPost;

class InheritPost extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'inheritpost',
            __('Post article', 'modularity'),
            __('Post article', 'modularity'),
            __('Outputs title and content from any post or page', 'modularity'),
            array(''),
            null
        );
    }
}
