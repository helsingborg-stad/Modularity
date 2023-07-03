<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Tag as TagHelper;

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
        $this->data['imagePosition'] = $fields->image_position;

        $this->data['labels'] = [
            'readMore' => __('Read more', 'modularity'),
        ];

        $this->preparePosts();
    }


    public function preparePosts()
    {
        foreach ($this->data['posts'] as $post) {  
            $this->setPostFlags($post);
            echo '<pre>' . print_r( $post, true ) . '</pre>';
        }
    }
}
