<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class ExpandableListTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class ExpandableListTemplate
{
    protected $module;
    protected $args;

    public $data = array();

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));
        $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->module->post_type, $this->args));

        $this->data['posts_list_column_titles'] = !empty($fields->posts_list_column_titles) && is_array($fields->posts_list_column_titles) ? $fields->posts_list_column_titles : null;
        $this->data['posts_hide_title_column'] = !isset($fields->posts_hide_title_column) || !$fields->posts_hide_title_column;
        $this->data['title_column_label'] = isset($fields->title_column_label) ? $fields->title_column_label : false;
        $this->data['allow_freetext_filtering'] = $fields->allow_freetext_filtering ?? null;

        $this->data['prepareAccordion'] = $this->prepare($this->module->data['posts'], $this->data);
    }

    /**
     * get correct column values
     * @return array|mixed|void
     */
    public function getColumnValues()
    {
        if (empty($this->data['posts_list_column_titles'])) {
            return;
        }

        $column_values = array();

        foreach ($this->data['posts'] as $post) {

            if ($this->data['posts_data_source'] === 'input') {
                if ($post->column_values !== false && count($post->column_values) > 0) {
                    foreach ($post->column_values as $key => $columnValue) {
                        $column_values[sanitize_title($this->data['posts_list_column_titles'][$key]->column_header)] = $columnValue->value;
                    }
                }
            } else {
                $column_values = get_post_meta($post->ID, 'modularity-mod-posts-expandable-list', true);
            }
        }

        return $column_values;

    }

    /**
     * Prepare Data for accordion
     * @param $posts
     * @param $data
     * @return array|null
     */
    public function prepare($posts, $data)
    {

        $column_values = $this->getColumnValues();
        $accordion = array();

        if (count($posts) > 0) {

            foreach ($posts as $index => $post) {

                $taxPosition = ($data['taxonomyDisplay']['top']) ? $data['taxonomyDisplay']['top'] :
                    $data['taxonomyDisplay']['below'];

                $accordion[$index]['taxonomy'] = (new \Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $taxPosition);
                $accordion[$index]['taxonomyPosition'] = $taxPosition;

                if (!empty($data['posts_list_column_titles'])) {

                    if (isset($column_values) && !empty($column_values)) {

                        if ($data['posts_hide_title_column']) {
                            $accordion[$index]['heading'] = apply_filters('the_title',
                                $post->post_title);
                        }

                        if (is_array($data['posts_list_column_titles'])) {
                            foreach ($data['posts_list_column_titles'] as $column) {
                                $accordion[$index]['heading'] .= isset(
                                    $column_values[sanitize_title($column->column_header)]) ?
                                    $column_values[sanitize_title($column->column_header)] : '';
                            }
                        }

                    } else {
                        $accordion[$index]['heading'] = apply_filters('the_title', $post->post_title);
                    }

                } else {
                    $accordion[$index]['heading'] = apply_filters('the_title', $post->post_title);
                }

                $accordion[$index]['content'] = apply_filters('the_content', $post->post_content);
            }
        }

        if ($accordion < 0)
            return null;

        return $accordion;
    }

}