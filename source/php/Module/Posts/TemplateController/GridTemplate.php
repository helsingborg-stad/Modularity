<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;
use Modularity\Module\Posts\Helper\Tag as TagHelper;

class GridTemplate extends AbstractController
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

        $data['display_reading_time'] = $fields->posts_fields['reading_time'] ?? false;

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio;
        $this->data['classes'] = implode(' ', apply_filters(
            'Modularity/Module/Classes',
            [],
            $this->module->post_type ?? '',
            $this->args
        ));

        if ($fields->posts_highlight_first ?? false) {
            $this->data['highlight_first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
            $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        } else {
            $this->data['highlight_first_column'] = false;
        }

        $this->data['gridSize'] = (int)str_replace('-', '', filter_var($fields->posts_columns, FILTER_SANITIZE_NUMBER_INT));
        $this->data['column_width'] = 'o-grid-' . $this->data['gridSize'] . '@md';

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

            $tagData = TagHelper::getTags(
                $post->ID,
                $this->data['taxonomyDisplayFlat'],
                $post->link
            );

            $post->tags = $tagData['tags'];
            $post->termIcon = $tagData['termIcon'];

            if (class_exists('Municipio\Helper\ReadingTime')) {
                $post->reading_time = \Municipio\Helper\ReadingTime::getReadingTime($post->post_content, 0, true);
            } else {
                $post->reading_time = false;
            }
            $this->setPostFlags($post);
        }
    }
}
