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

        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', [], $module->post_type, $this->args));
    }

    /**
     * @param $posts
     * @param $postData
     * @return array
     */
    public function prepare($posts, $postData)
    {
        $list = [];

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
                $columnsTitle = $post->post_title;
            }

            if (in_array('date', $postData['posts_fields']) && $postData['posts_data_source'] !== 'input') {
                $columnsDate = apply_filters(
                    'Modularity/Module/Posts/Date',
                    get_the_time(\Modularity\Helper\Date::getDateFormat('date'), $post->ID),
                    $post->ID,
                    $post->post_type
                );
            } else {
                $columnsDate = '';
            }

            array_push($list, ['href' => $href ?? '', 'columns' => [$columnsTitle, $columnsDate]]);
        }

        if (
            $postData['posts_data_source'] !== 'input' &&
            isset($postData['archive_link']) && $postData['archive_link'] && $postData['archive_link_url']
        ) {

            $columnsTitle = _e('Show more', 'modularity');

            if (isset($postData['filters'])) {
                $href = $postData['archive_link_url'] . "?" . http_build_query($postData['filters']);
            }

            array_push($list, ['href' => $href ?? '', 'columns' => [$columnsTitle]]);
        }
        return $list;
    }
}
