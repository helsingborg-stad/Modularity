<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class SegmentTemplate
 *
 * Template controller for rendering posts as Segment.
 *
 * @package Modularity\Module\Posts\TemplateController
 * 
 */
class SegmentTemplate extends AbstractController
{
    /**
     * The instance of the Posts module associated with this template.
     *
     * @var \Modularity\Module\Posts\Posts
     */
    protected $module;

    /**
     * The arguments passed to the template controller.
     *
     * @var array
     */
    protected $args;

    /**
     * Data to be used in rendering the template.
     *
     * @var array
     */
    public $data = [];

    /**
     * Acf fields.
     *
     * @var object
     */
    public $fields;

    /**
     * SegmentTemplate constructor.
     *
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     * @param array $args Arguments passed to the template controller.
     * @param array $data Data to be used in rendering the template.
     * @param object $fields Object containing prepared fields for rendering.
     */
    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, array $data, object $fields)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $this->prepareFields($fields);
        $this->preparePosts();
    }
}
