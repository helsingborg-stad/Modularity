<?php

namespace Modularity\Module\Menu\TemplateController;

interface MenuTemplateInterface
{
    public function __construct(array $menuItems, array $fields);
    public function getStructuredMenuItems(): array;
}