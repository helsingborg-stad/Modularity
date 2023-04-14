<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Tag as TagHelper;

class SegmentTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = (object) json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['imagePosition'] = $fields->image_position;

        $this->data['labels'] = [
            'readMore' => __('Read more', 'modularity'),
        ];

        $this->preparePosts();

        // $this->data['anyPostHasImage'] = $this->anyPostHasImage($this->data['posts']);
    }


    public function preparePosts()
    {
        $amount          = $this->getTruncateAmount($this->data['posts_display_as']);
        $imageDimensions = $this->getImageDimensions($this->data['posts_columns']);

        foreach ($this->data['posts'] as $post) {
            $post->classList = [];

            $image = $this->getPostImage($post, $this->data['posts_data_source'], $imageDimensions, '16:9');
            $post->thumbnail = (array) apply_filters('Modularity/Module/Posts/thumbnail', $image);
            if (empty($post->thumbnail[0])) {
                $post->thumbnail[0] = \Modularity\Helper\Wp::getThemeMod('logotype_emblem') ?: get_stylesheet_directory_uri() . '/assets/images/broken_image.svg';
                $post->classList = ['has-emblem'];
            }

            // Get link for card, or tags
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);

            $tagData = TagHelper::getTags(
                $post->ID,
                $this->data['taxonomyDisplayFlat'],
                $post->link
            );

            $post->tags = $tagData['tags'] ?? false;
            $post->termIcon = $tagData['termIcon'] ?? false;

            // Get excerpt
            $post->post_content = $this->truncateExcerpt($post->post_content, $amount);

            $this->setPostFlags($post);
        }
    }
}
