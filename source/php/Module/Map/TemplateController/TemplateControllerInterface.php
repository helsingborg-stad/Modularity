<?php

namespace Modularity\Module\Map\TemplateController;

interface TemplateControllerInterface
{
    public function addData(array $data, array $fields): array;
    public function canHandle(array $fields): bool;
    public function getTemplateName(): string;
}