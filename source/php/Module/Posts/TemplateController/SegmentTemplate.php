<?php

namespace Modularity\Module\Posts\TemplateController;

class SegmentTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = (object) json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['imagePosition'] = !empty($fields->image_position) ? $fields->image_position : false;

        $this->data['labels'] = [
            'readMore' => __('Read more', 'modularity'),
        ];

        $this->preparePosts();
    }
}
