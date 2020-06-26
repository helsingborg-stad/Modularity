<?php

namespace Modularity\Module\Posts\TemplateController;

class ListTemplate
{
    protected $module;
    protected $args;

    public $data = array();

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->module->post_type, $this->args));
    }

    /**
     * Prepare List for list component
     * @param $posts
     * @param $postData
     * @return array || null
     */
    public function prepare($posts, $postData)
    {
        $list = array();
        $index = 0;

        if (count($posts) < 1) {
            $list[0]['label'] = _e('No posts to show…', 'modularity');
            return $list;
        }

        foreach ($posts as $index => $post) {

            if (!empty($post->post_type) && $post->post_type == 'attachment') {
                $list[$index]['href'] = wp_get_attachment_url($post->ID);
            } else {
                $list[$index]['href'] = $postData['posts_data_source'] === 'input' ?
                    $post->permalink : get_permalink($post->ID);
            }

            if (in_array('title', $postData['posts_fields'])) {
                $list[$index]['label'] = apply_filters('the_title', $post->post_title);
            }

            if (in_array('date',
                    $postData['posts_fields']) &&
                $postData['posts_data_source'] !== 'input') {

                $list[$index]['label'] .= apply_filters('Modularity/Module/Posts/Date',
                    get_the_time('Y-m-d', $post->ID), $post->ID, $post->post_type);
            }
        }

        if ($postData['posts_data_source'] !== 'input' &&
            isset($postData['archive_link']) && $postData['archive_link'] &&
            $postData['archive_link_url']) {

            $list[$index]['label'] = _e('Show more', 'modularity');
            if (isset($postData['filters'])) {
                $list[$index]['href'] = $postData['archive_link_url'] . "?" . http_build_query
                    ($postData['filters']);
            }

        }

        return $list;

    }
}
