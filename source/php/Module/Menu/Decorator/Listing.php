<?php

namespace Modularity\Module\Menu\Decorator;

class Listing implements DataDecoratorInterface
{
    public function __construct(private $fields)
    {}

    public function decorate(array $data): array
    {
        $data['columns'] = $this->getListingColumns();

        return $data;
    }

    private function getListingColumns()
    {
        $columns = $this->fields['columns'] ?? 3;
        
        return $columns;
    }
}