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

        $this->data['prepareList'] = $this->prepare($this->module->data['posts'], $postData = array(
            'posts_data_source' => $this->module->data['posts_data_source'] ?? '',
            'posts_fields' => $this->module->data['posts_fields'] ?? '',
            'archive_link' => $this->module->data['archive_link'] ?? '',
            'archive_link_url' => $this->module->data['archive_link_ur'] ?? '',
            'filters' => $this->module->data['filters'] ?? ''
        ));

        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array(), $this->module->post_type, $this->args));
    }

    /**
     * @param $posts
     * @param $postData
     * @return array
     */
    public function prepare($posts, $postData)
    {
        $list = array();

        if (count($posts) < 1) {
            array_push($list, ['columns' => _e('No posts to show…', 'modularity')]);
            return $list;
        }

        foreach ($posts as $post) {

            if (!empty($post->post_type) && $post->post_type == 'attachment') {
                $href = wp_get_attachment_url($post->ID);
            } else {
                $href = $postData['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);
            }

            if (in_array('title', $postData['posts_fields'])) {
                $columnsTitle = apply_filters('the_title', $post->post_title);
            }

            if (in_array('date', $postData['posts_fields']) && $postData['posts_data_source'] !== 'input') {
                $columnsDate = apply_filters('Modularity/Module/Posts/Date', get_the_time('Y-m-d', $post->ID),
                    $post->ID, $post->post_type) ;
            } else {
                $columnsDate = '';
            }


            array_push($list, ['href' => $href ?? '', 'columns' => [$columnsTitle, $columnsDate]]);

        }

        if ($postData['posts_data_source'] !== 'input' &&
            isset($postData['archive_link']) && $postData['archive_link'] && $postData['archive_link_url']) {

            $columnsTitle = _e('Show more', 'modularity');

            if (isset($postData['filters'])) {
                $href = $postData['archive_link_url'] . "?" . http_build_query
                    ($postData['filters']);
            }

            array_push($list, ['href' => $href ?? '', 'columns' => [$columnsTitle]]);
        }

        return $list;

    }
}
