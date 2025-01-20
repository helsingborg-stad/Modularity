<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class ListTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class ListTemplate extends AbstractController
{
    protected $args;
    public $data = [];

    /**
     * ListTemplate constructor.
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     * @param array $args Arguments passed to the template controller
     * @param array $data Data to be used in the template
     * @param object $fields Object containing ACF fields
     */
    public function __construct(\Modularity\Module\Posts\Posts $module)
    {
        $this->args = $module->args;
        $this->data = $module->data;
        $this->data['posts'] = $this->prepare([
            'posts_data_source' => $this->data['posts_data_source'] ?? '',
            'archive_link' => $this->data['archive_link'] ?? '',
            'archive_link_url' => $this->data['archive_link_url'] ?? '',
            'filters' => $this->data['filters'] ?? '',
        ]);
    }

    /**
     * @param array $posts array of posts
     * @param array $postData array of data settings
     * @return array
     */
    public function prepare(array $postData)
    {
        $posts = [];
        if (!empty($this->data['posts']) && is_array($this->data['posts'])) {
            $this->data['posts'] = $this->preparePosts($this->data['posts']);
            foreach ($this->data['posts'] as $post) {
                
                if ($post->getPostType() === 'attachment') {
                    $post->permalink = wp_get_attachment_url($post->getId());
                }

                $post->icon      = 'arrow_forward';
                $post->classList = $post->classList ?? [];
                $post->attributeList = ['data-js-item-id' => $post->getId()]; 

                if(boolval(($this->data['meta']['use_term_icon_as_icon_in_list'] ?? false))) {
                    $post->icon = $post->getIcon()?->toArray() ?: 'arrow_forward';
                }

                $posts[] = $post;
            }
        }

        return $posts;
    }
}
