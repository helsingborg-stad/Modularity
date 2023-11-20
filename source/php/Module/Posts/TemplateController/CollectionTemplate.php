<?php

namespace Modularity\Module\Posts\TemplateController;

class CollectionTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data, $fields)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);

        $this->preparePosts();
        $this->data['anyPostHasImage'] = $this->anyPostHasImage($this->data['posts']);
    }
}