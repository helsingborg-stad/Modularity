<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class FeaturesGridTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class FeaturesGridTemplate extends AbstractController
{
    protected $module;
    protected $args;
    public $data = [];

    /**
     * FeaturesGridTemplate constructor.
     * @param \Modularity\Module\Posts\Posts $module
     * @param array $args
     * @param $data
     */
    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data, $fields)
    {
        $this->hookName = 'featuresGrid';
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $this->prepareFields($fields);
        $this->preparePosts();
    }
}
