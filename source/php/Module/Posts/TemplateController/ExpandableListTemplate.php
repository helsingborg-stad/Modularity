<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Tag;

/**
 * Class ExpandableListTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class ExpandableListTemplate
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['classes'] = implode(
            ' ',
            apply_filters('Modularity/Module/Classes', [], $this->module->post_type, $this->args)
        );
        $this->data['posts_list_column_titles'] = !empty($fields->posts_list_column_titles) && is_array($fields->posts_list_column_titles) ?
            $fields->posts_list_column_titles : null;
        $this->data['posts_hide_title_column'] = ($fields->posts_hide_title_column) ? true : false;
        $this->data['title_column_label'] = $fields->title_column_label ?? null;
        $this->data['allow_freetext_filtering'] = $fields->allow_freetext_filtering ?? null;
        $this->data['prepareAccordion'] = $this->prepare($data['posts'], $this->data);
    }

    /**
     * Get correct column values
     * @return array
     */
    public function getColumnValues(): array
    {
        if (empty($this->data['posts_list_column_titles'])) {
            return [];
        }

        $columnValues = [];

        foreach ($this->data['posts'] as $colIndex => $post) {
            if ($this->data['posts_data_source'] === 'input') {
                if ($post->column_values !== false && count($post->column_values) > 0) {
                    foreach ($post->column_values as $key => $columnValue) {
                        $columnValues[$colIndex][sanitize_title($this->data['posts_list_column_titles'][$key]->column_header)] = $columnValue->value ?? '';
                    }
                }
            } else {
                $columnValues[] = get_post_meta($post->ID, 'modularity-mod-posts-expandable-list', true) ?? '';
            }
        }

        return $columnValues;
    }

    /**
     * Prepare Data for accordion
     * @param $posts
     * @param $data
     * @return array|null
     */
    public function prepare($posts, $data): ?array
    {
        $columnValues = $this->getColumnValues();

        $accordion = [];

        if (count($posts) > 0) {
            foreach ($posts as $index => $post) {
                $taxPosition = '';
                if ($this->hasTaxonomyDisplayPosition($data['taxonomyDisplay'])) {
                    $taxPosition = ($data['taxonomyDisplay']['top']) ?: $data['taxonomyDisplay']['below'];
                }

                $accordion[$index]['taxonomy'] = (new Tag)->getTags($post->ID, $taxPosition);
                $accordion[$index]['taxonomyPosition'] = $taxPosition;

                if ($this->hasColumnValues($data, $columnValues) && $this->hasColumnTitles($data)) {
                    foreach ($data['posts_list_column_titles'] as $colIndex => $column) {
                        $sanitizedTitle = sanitize_title($column->column_header);
                        if ($this->arrayDepth($columnValues) > 1) {
                            $accordion[$index]['column_values'][$colIndex] = $columnValues[$index][$sanitizedTitle] ?? '';
                        } else {
                            $accordion[$index]['column_values'][$colIndex] = $columnValues[$sanitizedTitle] ?? '';
                        }
                    }
                }

                $accordion[$index]['heading'] = apply_filters('the_title', $post->post_title) ?? '';
                $accordion[$index]['content'] = apply_filters('the_content', $post->post_content) ?? '';
            }
        }

        if ($accordion < 0)
            return null;

        return $accordion;
    }

    /**
     * Get array dimension depth
     * @param array $colArray
     * @return int
     */
    public function arrayDepth(array $colArray): int
    {
        $maxDepth = 1;
        foreach ($colArray as $value) {
            if (is_array($value)) {
                $depth = $this->arrayDepth($value) + 1;
                $maxDepth = ($depth > $maxDepth) ? $depth : $maxDepth;
            }
        }

        return $maxDepth;
    }

    private function hasTaxonomyDisplayPosition($taxonomyDisplay): bool
    {
        return (isset($taxonomyDisplay['top']) && !empty($taxonomyDisplay['top'])) ||
            (isset($taxonomyDisplay['below']) && !empty($taxonomyDisplay['below']));
    }

    private function hasColumnValues($data, $columnValues): bool
    {
        return isset($columnValues)
            && !empty($columnValues);
    }

    private function hasColumnTitles($data): bool
    {
        return !empty($data['posts_list_column_titles'])
            && is_array($data['posts_list_column_titles']);
    }
}
