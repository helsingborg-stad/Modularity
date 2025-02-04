<?php

namespace Modularity\Module\Map\TemplateController;

/**
 * Class NullController
 * @package Modularity\Module\Map\TemplateController
 */
class NullController implements TemplateControllerInterface
{
    /**
     * Add data to the template.
     */
    public function addData(array $data, array $fields): array
    {
        return $data;
    }

    public function canHandle(array $fields): bool
    {
        return true;
    }
}