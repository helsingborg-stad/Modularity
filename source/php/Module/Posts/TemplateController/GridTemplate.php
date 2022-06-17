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

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio;
        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', [], $this->module->post_type, $this->args));

        if ($fields->posts_highlight_first ?? false) {
            $this->data['highlight_first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
            $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        }

        $this->data['gridSize'] = (int)str_replace('-', '', filter_var($fields->posts_columns, FILTER_SANITIZE_NUMBER_INT));
        $this->data['column_width'] = 'o-grid-' . $this->data['gridSize'] . '@md';
        $this->data['column_height'] = false;

        $this->prepare($fields);
        $this->data['anyPostHasImage'] = $this->anyPostHasImage($this->data['posts']);
    }

    public function prepare($fields)
    {
        $postNum = 0;
        $gridRand = $this->getGridPattern($this->data['gridSize']);
        $gridRow = [];

        /* Image size */
        $imageDimensions = [1200, 900];

        if (!$fields->posts_alter_columns) {
            $imageDimensions = $this->getImageDimensions($fields->posts_columns, [900, 675]);
        }

        foreach ($this->data['posts'] as $post) {
            $postNum++;

            // Get altering grid size
            if ($fields->posts_alter_columns) {
                if (empty($gridRow)) {
                    $gridRow = $gridRand;
                }

                if (empty($gridColumns)) {
                    $gridColumns = $gridRow[0];
                    array_shift($gridRow);
                }

                $columnSize = 'o-grid-' . $gridColumns[0] . '@md';
                array_shift($gridColumns);
                $columnHeight = $this->getColumnHeight($this->data['gridSize']);

                $post->column_width = $columnSize;
                $post->column_height = $columnHeight;
            }

            /* Image */
            $post->thumbnail = $this->getPostImage($post, $this->data['posts_data_source'], $imageDimensions, $fields->ratio);

            // Get link for card, or tags
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);
            $post->tags = (new TagHelper)->getTags($post->ID, $this->data['taxonomyDisplayFlat']);

            $this->setPostFlags($post);
        }
    }

    public function getGridPattern($gridSize)
    {
        $gridRand = [];

        switch ($gridSize) {
            case 12:
                $gridRand = [
                    [12]
                ];
                break;

            case 6:
                $gridRand = [
                    [12],
                    [6, 6],
                    [6, 6]
                ];
                break;

            case 4:
                $gridRand = [
                    [8, 4],
                    [4, 4, 4],
                    [4, 8]
                ];
                break;

            case 3:
                $gridRand = [
                    [6, 3, 3],
                    [3, 3, 3, 3],
                    [3, 3, 6],
                    [3, 3, 3, 3],
                    [3, 6, 3]
                ];
                break;

            default:
                $gridRand = [
                    [12]
                ];
                break;
        }

        return apply_filters('Modularity/Module/Posts/TemplateController/BlockTemplate/Pattern', $gridRand, $gridSize);
    }

    private function getColumnHeight($gridSize): ?string
    {
        switch ($gridSize) {
            case 3:
                return '280px';
            case 4:
                return '400px';
            case 6:
                return '500px';
            case 12:
                return '500px';
            default:
                return null;
        }
    }
}
