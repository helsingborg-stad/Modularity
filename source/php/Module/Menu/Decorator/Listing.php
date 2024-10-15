<?php

namespace Modularity\Module\Menu\Decorator;

class Listing implements DataDecoratorInterface
{
    public function __construct(private $fields)
    {}

    public function decorate(array $data): array
    {
        $data['columns'] = $this->fields['mod_menu_columns'] ?? 3;

        return $data;
    }
}