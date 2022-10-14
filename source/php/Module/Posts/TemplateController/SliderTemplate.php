<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;
use Modularity\Module\Posts\Helper\Tag as TagHelper;

class SliderTemplate extends AbstractController
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

        $this->data['slides_per_page'] = $fields->slides_per_page;
        
        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio;
        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', [], $this->module->post_type, $this->args));

        // $this->data['gridSize'] = (int)str_replace('-', '', filter_var($fields->posts_columns, FILTER_SANITIZE_NUMBER_INT));
        // $this->data['column_width'] = 'o-grid-' . $this->data['gridSize'] . '@md';

        $this->prepare($fields);

        $this->data['anyPostHasImage'] = $this->anyPostHasImage($this->data['posts']);
    }

    public function prepare($fields)
    {
        $postNum = 0;

        /* Image size */
        $imageDimensions = [1200, 900];

        if (!$fields->posts_alter_columns) {
            $imageDimensions = $this->getImageDimensions($fields->posts_columns, [900, 675]);
        }

        foreach ($this->data['posts'] as $post) {
            $postNum++;

            /* Image */
            $post->thumbnail = $this->getPostImage($post, $this->data['posts_data_source'], $imageDimensions, $fields->ratio ?? '4:3');

            // Get link for card, or tags
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);
            $post->tags = (new TagHelper)->getTags($post->ID, $this->data['taxonomyDisplayFlat']);

            $this->setPostFlags($post);
        }
    }
}
