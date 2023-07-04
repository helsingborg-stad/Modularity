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
            'posts_fields' => $data['posts_fields'] ?? '',
            'archive_link_url' => $data['archive_link_ur'] ?? '',
            'filters' => $data['filters'] ?? '',
        ]);

        $this->data['classes'] = implode(
            ' ',
            apply_filters('Modularity/Module/Classes', [], $module->post_type, $this->args)
        );
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

        return $posts;
    }
}
