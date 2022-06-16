<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

class GridTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = array();

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio;
        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news'), $this->module->post_type, $this->args));

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
        $gridRow = array();

        /* Image size */
        $imageDimensions = array(1200, 900);

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
                $columnHeight = null;

                switch ($this->data['gridSize']) {
                    case 3:
                        $columnHeight = '280px';
                        break;

                    case 4:
                        $columnHeight = '400px';
                        break;

                    case 6:
                        $columnHeight = '500px';
                        break;

                    case 12:
                        $columnHeight = '500px';
                        break;

                    default:
                        $columnHeight = false;
                        break;
                }

                $post->column_width = $columnSize;
                $post->column_height = $columnHeight;
            }

            /* Image */
            $image = $this->getPostImage($post, $this->data['posts_data_source'], $imageDimensions, $fields->ratio);

            // Image fetch
            $post->thumbnail = $image;

            // Get link for card, or tags
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);
            $post->tags = (new \Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $this->data['taxonomyDisplayFlat']);

            $this->setPostBooleans($post);
        }
    }

    /**
     * Prepare a date to show in view
     *
     * @param   array $posts    The posts
     * @return  array           The posts - with archive date
     */
    public function getDate($post, $dateSource = 'post_date')
    {
        if (!$dateSource) {
            return false;
        }

        $isMetaKey = in_array($dateSource, ['post_date', 'post_modified']) ? false : true;

        if ($isMetaKey == true) {
            $postDate = get_post_meta($post->ID, $dateSource, true);
        } else {
            $postDate = $post->{$dateSource};
        }

        if (!is_string($postDate) || empty($postDate) || strtotime($postDate) === false) {
            $postDate = false;
        }

        return $postDate;
    }

    public function getGridPattern($gridSize)
    {
        $gridRand = array();

        switch ($gridSize) {
            case 12:
                $gridRand = array(
                    array(12)
                );
                break;

            case 6:
                $gridRand = array(
                    array(12),
                    array(6, 6),
                    array(6, 6)
                );
                break;

            case 4:
                $gridRand = array(
                    array(8, 4),
                    array(4, 4, 4),
                    array(4, 8)
                );
                break;

            case 3:
                $gridRand = array(
                    array(6, 3, 3),
                    array(3, 3, 3, 3),
                    array(3, 3, 6),
                    array(3, 3, 3, 3),
                    array(3, 6, 3)
                );
                break;

            default:
                $gridRand = array(
                    array(12)
                );
                break;
        }

        return apply_filters('Modularity/Module/Posts/TemplateController/BlockTemplate/Pattern', $gridRand, $gridSize);
    }
}
