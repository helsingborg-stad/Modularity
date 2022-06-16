<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

class ItemsTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->hookName = 'items';
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', ['box', 'box-news'], $this->module->post_type, $this->args));

        if ($fields->posts_highlight_first ?? false) {
            $this->data['highlight_first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
            $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        }

        $this->getImages($fields);
    }

    public function getImages($fields)
    {
        $imageDimension = $this->getImageDimensions($this->data['posts_columns']);

        foreach ($this->data['posts'] as $post) {
            $image = $this->getPostImage($post, $fields->posts_data_source, $imageDimension, '16:9');

            $post->thumbnail = $image;
        }
    }
}
