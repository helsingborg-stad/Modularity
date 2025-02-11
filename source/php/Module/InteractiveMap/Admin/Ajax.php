<?php

namespace Modularity\Module\InteractiveMap\Admin;

use WpService\WpService;

class Ajax
{
    public function __construct(private WpService $wpService, private GetTaxonomies $taxonomiesHelper)
    {
        $this->wpService->addAction('wp_ajax_interactive_map_get_taxonomies', array($this, 'getTaxonomies'));
    }

    public function getTaxonomies()
    {
        $taxonomies = $this->taxonomiesHelper->getTaxonomies();

        $this->wpService->wpSendJson($this->taxonomiesHelper->getTaxonomies());
    }
}