<?php

namespace Modularity\Module\Posts\TemplateController;

class ItemsTemplate
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

        $this->getImages($fields);
    }

    public function getImages($fields)
    {
        $imageDimension = array(400, 300);
        switch ($this->data['posts_columns']) {
            case "grid-md-12":    //1-col
                $imageDimension = array(1200, 900);
                break;

            case "grid-md-6":    //2-col
                $imageDimension = array(800, 600);
                break;
        }

        foreach ($this->data['posts'] as $post) {
            /* Image */
            $image = null;
            if ($fields->posts_data_source !== 'input') {
                $image = wp_get_attachment_image_src(
                    get_post_thumbnail_id($post->ID),
                    apply_filters(
                        'modularity/image/posts/items',
                        municipio_to_aspect_ratio('16:9', $imageDimension),
                        $this->args
                    )
                );
            } else {
                if ($post->image) {
                    $image = wp_get_attachment_image_src(
                        $post->image->ID,
                        apply_filters(
                            'modularity/image/posts/items',
                            municipio_to_aspect_ratio('16:9', $imageDimension),
                            $this->args
                        )
                    );
                }
            }

            $post->thumbnail = $image;
        }
    }
}
