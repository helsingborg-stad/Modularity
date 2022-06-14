<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class ListTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class FeaturesGridTemplate
{
    protected $module;
    protected $args;
    public $data = array();

    /**
     * ListTemplate constructor.
     * @param \Modularity\Module\Posts\Posts $module
     * @param array $args
     * @param $data
     */
    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio;
        $this->data['classes'] = apply_filters('Modularity/Module/Classes', array('u-height--100', 'u-height-100'), $module->post_type, $args);
        $this->preparePosts($fields);
    }

    public function preparePosts($fields)
    {
        $postNum = 0;

        foreach ($this->data['posts'] as $post) {
            $postNum++;

            /* Image */
            $image = null;
            $imageDimensions = [400,225];
            if ($this->data['posts_data_source'] !== 'input') {
                $image = wp_get_attachment_image_src(
                    get_post_thumbnail_id($post->ID),
                    apply_filters(
                        'modularity/image/posts/featuresGrid',
                        municipio_to_aspect_ratio('16:9', $imageDimensions),
                        $this->args
                    )
                );
            } else {
                if ($post->image) {
                    $image = wp_get_attachment_image_src(
                        $post->image->ID,
                        apply_filters(
                            'modularity/image/posts/featuresGrid',
                            municipio_to_aspect_ratio('16:9', $imageDimensions),
                            $this->args
                        )
                    );
                }
            }

            $post->thumbnail = $image;

            // Get link for card, or tags
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);
            $post->tags = (new \Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $this->data['taxonomyDisplayFlat']);
        
            //Booleans for hiding/showing stuff
            $post->showDate     = (bool) in_array('date', $this->data['posts_fields']);
            $post->showExcerpt  = (bool) in_array('excerpt', $this->data['posts_fields']);
            $post->showTitle    = (bool) in_array('title', $this->data['posts_fields']);
            $post->showImage    = (bool) in_array('image', $this->data['posts_fields']);

            if ($post->showDate) {
                $post->postDate = $this->getDate($post, $this->data['posts_date_source']);
            }
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
        if(!$dateSource) {
            return false;
        }

        $isMetaKey = in_array($dateSource, ['post_date', 'post_modified']) ? false : true;

        if($isMetaKey == true) {
            $postDate = get_post_meta($post->ID, $dateSource, true);
        } else {
            $postDate = $post->{$dateSource};
        }

        if (!is_string($postDate) || empty($postDate) || strtotime($postDate) === false) {
            $postDate = false;
        }

        return $postDate;
    }

}
