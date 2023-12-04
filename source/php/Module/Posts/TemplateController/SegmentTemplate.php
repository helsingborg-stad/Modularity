<?php

namespace Modularity\Module\Posts\TemplateController;

class SegmentTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data, $fields)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;
        
        $this->prepareFields($fields);
        $this->preparePosts();
    }
}
