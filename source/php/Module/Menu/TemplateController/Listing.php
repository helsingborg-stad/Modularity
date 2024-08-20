<?php

namespace Modularity\Module\Menu\TemplateController;

use Modularity\Module\Menu\TemplateController\MenuTemplateInterface;

class Listing implements MenuTemplateInterface {
    public function __construct() {
    
    }
    public function getStructuredMenuItems(): array {
        return [];
    }
}