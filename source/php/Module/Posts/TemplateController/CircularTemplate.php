<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

class CircularTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->hookName = 'news';
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));
        $classes = apply_filters('Modularity/Module/Classes', ['no-color'], $this->module->post_type, $this->args);
        $this->data['classes'] = implode(' ', $classes);
        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);

        if ($fields->posts_highlight_first ?? false) {
            $this->data['highlight_first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
            $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        }

        $this->getThumbnails();
    }

    public function getThumbnails()
    {
        $hasImages = false;

        foreach ($this->data['posts'] as &$post) {
            $imageDimensions = [400, 400];
            $image = $this->getPostImage($post, $this->data['posts_data_source'], $imageDimensions, '1:1');

            if ($image) {
                $hasImages = true;
            }

            $post->thumbnail = $image;
        }

        $this->data['hasImages'] = $hasImages;
    }
}
