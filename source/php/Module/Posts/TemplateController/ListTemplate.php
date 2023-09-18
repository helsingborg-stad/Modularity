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
     * @param \Modularity\Module\Posts\Posts $module
     * @param array $args
     * @param $data
     */
    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
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
     * @param $posts
     * @param $postData
     * @return array
     */
    public function prepare($posts, $postData)
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
