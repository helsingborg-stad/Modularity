<?php

namespace Modularity\Module\InteractiveMap\Admin;

use WpService\WpService;

class AcfFilters
{
    public function __construct(private WpService $wpService)
    {
        // die;
        if ($this->wpService) {
            // $this->wpService->addFilter()
        }
    }
}