<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class ListTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class ListTemplate
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
        $this->data['prepareList'] = $this->prepare([
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
        $list = [];
        if (!empty($this->data['posts']) && is_array($this->data['posts'])) {
            foreach ($this->data['posts'] as $post) {
                if (!empty($post->postType) && $post->postType == 'attachment') {
                    $link = wp_get_attachment_url($post->id);
                } else {
                    $link = $postData['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->id);
                }
    
                if (!empty($post->postTitle)) {
                    array_push($list, ['link' => $link ?? '', 'title' => $post->postTitle]);
                }
            }
        }

        return $list;
    }
}
