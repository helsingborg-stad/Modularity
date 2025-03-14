<?php

namespace Modularity\Module\Map\Resolvers;

use Modularity\Module\Map\TemplateController\TemplateControllerInterface;

class TemplateResolver implements TemplateResolverInterface
{
    private array $templateControllers;

    public function __construct(TemplateControllerInterface ...$templateControllers)
    {
        $this->templateControllers = $templateControllers;
    }

    public function resolve(array $fields): TemplateControllerInterface
    {
        foreach ($this->templateControllers as $templateController) {
            if ($templateController->canHandle($fields)) {
                return $templateController;
            }
        }

        throw new \Exception('No template controller found');
    }
}