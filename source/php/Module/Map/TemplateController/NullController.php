<?php

namespace Modularity\Module\Map\TemplateController;

/**
 * Class NullController
 * @package Modularity\Module\Map\TemplateController
 */
class NullController implements TemplateControllerInterface
{
    private string $templateName = 'notFound';

    /**
     * Add data to the template.
     */
    public function addData(array $data, array $fields): array
    {
        return $data;
    }

    /**
     * Get the template name.
     */
    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * Check if the controller can handle the fields.
     */
    public function canHandle(array $fields): bool
    {
        return true;
    }
}