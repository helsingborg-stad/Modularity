<?php

namespace Modularity\Module\Map\Resolvers;

use Modularity\Module\Map\TemplateController\TemplateControllerInterface;

interface TemplateResolverInterface
{
    public function resolve(array $fields): TemplateControllerInterface;
}

