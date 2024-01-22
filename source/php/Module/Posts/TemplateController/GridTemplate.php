<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class GridTemplate
 *
 * Template controller for rendering posts as a grid.
 *
 * @package Modularity\Module\Posts\TemplateController
 */
class GridTemplate extends AbstractController
{
    /**
     * GridTemplate constructor.
     *
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     * @param array $args Arguments passed to the template controller.
     * @param array $data Data to be used in rendering the template.
     * @param object $fields Object containing prepared fields for rendering.
     */
    public function __construct(\Modularity\Module\Posts\Posts $module)
    {
        parent::__construct($module);
    }
}
