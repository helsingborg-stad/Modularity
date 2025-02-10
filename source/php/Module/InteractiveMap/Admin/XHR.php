<?php

namespace Modularity\Module\InteractiveMap\Admin;

use WpService\WpService;

class XHR
{
    public function __construct(private WpService $wpService)
    {
        $this->wpService->addAction('wp_ajax_get_taxonomy_types_v2', array($this, 'getTaxonomyTypes'));
    }
}