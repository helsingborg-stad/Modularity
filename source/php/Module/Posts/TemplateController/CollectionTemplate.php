<?php

namespace Modularity\Module\Posts\TemplateController;

class CollectionTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);

        $this->preparePosts();
        $this->data['anyPostHasImage'] = $this->anyPostHasImage($this->data['posts']);
    }
}