<?php

namespace Modularity\Module\Posts\TemplateController;

class NewsTemplate
{
    protected $module;
    protected $args;

    public $data = array();

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news', 'box-news-horizontal'), $this->module->post_type, $this->args));
        $this->getThumbnails();
    }

    public function getThumbnails()
    {
        $hasImages = false;

        foreach ($this->data['posts'] as &$post) {
            $image_dimensions = array(400, 300);
            $image = false;

            if ($this->data['posts_data_source'] !== 'input') {
                $image = wp_get_attachment_image_src(
                    get_post_thumbnail_id($post->ID),
                    apply_filters(
                        'modularity/image/posts/news',
                        municipio_to_aspect_ratio('16:9', $image_dimensions),
                        $this->args
                    )
                );
            } else {
                if ($post->image) {
                    $image = wp_get_attachment_image_src(
                        $post->image->ID,
                        apply_filters(
                            'modularity/image/posts/news',
                            municipio_to_aspect_ratio('16:9', $image_dimensions),
                            $this->args
                        )
                    );
                }
            }

            if ($image) {
                $hasImages = true;
            }

            $post->thumbnail = $image;
        }

        $this->data['hasImages'] = $hasImages;
    }
}
