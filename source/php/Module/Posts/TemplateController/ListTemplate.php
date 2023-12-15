<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class ListTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class ListTemplate
{
    protected $module;
    protected $args;
    public $data = [];

    /**
     * ListTemplate constructor.
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     * @param array $args Arguments passed to the template controller
     * @param array $data Data to be used in the template
     * @param object $fields Object containing ACF fields
     */
    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, array $data, object $fields)
    {
        $this->args = $args;
        $this->data = $data;
        $this->data['prepareList'] = $this->prepare($data['posts'], $postData = [
            'posts_data_source' => $data['posts_data_source'] ?? '',
            'archive_link' => $data['archive_link'] ?? '',
            'archive_link_url' => $data['archive_link_ur'] ?? '',
            'filters' => $data['filters'] ?? '',
        ]);
    }

    /**
     * @param array $posts array of posts
     * @param array $postData array of data settings
     * @return array
     */
    public function prepare(array $posts, array $postData)
    {
        if(!is_array($postData)) {
            $postData = [$postData];
        }

        $list = [];
        foreach ($posts as $post) {
            if (!empty($post->postType) && $post->postType == 'attachment') {
                $link = wp_get_attachment_url($post->id);
            } else {
                $link = $postData['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->id);
            }

            if (!empty($post->postTitle)) {
                array_push($list, ['link' => $link ?? '', 'title' => $post->postTitle]);
            }
        }

        return $list;
    }
}
