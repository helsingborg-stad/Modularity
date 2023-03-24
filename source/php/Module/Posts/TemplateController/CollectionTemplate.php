<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

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
        $this->data['classes'] = apply_filters('Modularity/Module/Classes', ['u-height--100', 'u-height-100'], $module->post_type, $args);

        $this->preparePosts();
        $this->data['anyPostHasImage'] = $this->anyPostHasImage($this->data['posts']);
    }
}