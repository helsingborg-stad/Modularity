<?php

namespace Modularity\Module\Posts\TemplateController;

class GridTemplate
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

        $this->data['posts_columns'] = $fields->posts_columns;
        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news'), $this->module->post_type, $this->args));

        $this->data['gridSize'] = (int)str_replace('-', '', filter_var($fields->posts_columns, FILTER_SANITIZE_NUMBER_INT));
        $this->data['column_width'] = 'o-grid-' . $this->data['gridSize'].'@md';
        $this->data['column_height'] = false;

        $this->preparePosts($fields);
    }

    public function preparePosts($fields)
    {
        $postNum = 0;
        $gridRand = $this->getGridPattern($this->data['gridSize']);
        $gridRow = array();

        /* Image size */
        $imageDimensions = array(1200, 900);

        if (!$fields->posts_alter_columns) {
            switch ($fields->posts_columns) {
                case "o-grid-12@md":    //1-col
                    $imageDimensions = array(1200, 900);
                    break;

                case "o-grid-6@md":    //2-col
                    $imageDimensions = array(900, 675);
                    break;

                default:
                    $imageDimensions = array(900, 675);
            }
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

                $columnSize = 'o-grid-' . $gridColumns[0].'@md';
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
            $image = null;
            if ($fields->posts_data_source !== 'input') {
                $image = wp_get_attachment_image_src(
                    get_post_thumbnail_id($post->ID),
                    apply_filters(
                        'modularity/image/posts/items',
                        municipio_to_aspect_ratio('16:9', $imageDimensions),
                        $this->args
                    )
                );
            } else {
                if ($post->image) {
                    $image = wp_get_attachment_image_src(
                        $post->image->ID,
                        apply_filters(
                            'modularity/image/posts/items',
                            municipio_to_aspect_ratio('16:9', $imageDimensions),
                            $this->args
                        )
                    );
                }
            }

            $post->thumbnail = $image;
            $post->extended = get_extended(wp_strip_all_tags(strip_shortcodes($post->post_content)));
        }
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

        return apply_filters('Modularity/Module/Posts/TemplateController/GridTemplate/Pattern', $gridRand, $gridSize);
    }
}
