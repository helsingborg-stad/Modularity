<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class ListTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class FeaturesGridTemplate extends AbstractController
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

    public function preparePosts()
    {
        $imageDimensions = [400, 225];

        foreach ($this->data['posts'] as $post) {
            $image = $this->getPostImage($post, $this->data['posts_data_source'], $imageDimensions, '16:9', 'featuresGrid');

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
}
