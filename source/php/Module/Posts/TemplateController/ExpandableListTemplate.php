<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class ExpandableListTemplate
 *
 * Template controller for rendering posts as an expandable list.
 *
 * @package Modularity\Module\Posts\TemplateController
 */
class ExpandableListTemplate
{
    /**
     * The instance of the Posts module associated with this template.
     *
     * @var \Modularity\Module\Posts\Posts
     */
    protected $module;

    /**
     * The arguments passed to the template controller.
     *
     * @var array
     */
    protected $args;

    /**
     * Data to be used in rendering the template.
     *
     * @var array
     */
    public $data = [];

     /**
     * Fields ACF fields
     *
     * @var array
     */
    public $fields = [];

    /**
     * ExpandableListTemplate constructor.
     *
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     */
    public function __construct(\Modularity\Module\Posts\Posts $module)
    {
        $this->module = $module;
        $this->args = $module->args;
        $this->data = $module->data;
        $this->fields = $module->fields;

        $this->data['posts_list_column_titles'] = !empty($this->fields['posts_list_column_titles']) && is_array($this->fields['posts_list_column_titles']) ?
            $this->fields['posts_list_column_titles'] : null;

        $this->data['posts_hide_title_column'] = ($this->fields['posts_hide_title_column']) ? true : false;
        $this->data['title_column_label'] = $this->fields['title_column_label'] ?? null;
        $this->data['allow_freetext_filtering'] = $this->fields['allow_freetext_filtering'] ?? null;
        $this->data['prepareAccordion'] = $this->prepare();
    }

    /**
     * Get correct column values
     * @return array An array of column values for a column
     */
    public function getColumnValues(): array
    {

        if (empty($this->data['posts_list_column_titles'])) {
            return [];
        }
        
        $columnValues = [];
        
        foreach ($this->data['posts'] as $colIndex => $post) {
            if ($this->data['posts_data_source'] === 'input') {
                if ($post->columnValues !== false && is_array($post->columnValues) && count($post->columnValues) > 0) {
                    foreach ($post->columnValues as $key => $columnValue) {
                        $columnValues[$colIndex][sanitize_title($this->data['posts_list_column_titles'][$key]->column_header)] = $columnValue->value ?? '';
                    }
                }
            } else {
                $columnValues[] = get_post_meta($post->id, 'modularity-mod-posts-expandable-list', true) ?? '';
            }
        }

        return $columnValues;
    }

    /**
     * Prepare Data for accordion
     * @param array $items Array of posts
     * @param array $this->data Array of settings
     * 
     * @return array|null
     */
    public function prepare(): ?array
    {
        $columnValues = $this->getColumnValues();

        $accordion = [];

        if (!empty($this->data['posts']) && is_array($this->data['posts'])) {
            foreach ($this->data['posts'] as $index => $item) {
                if ($this->hasColumnValues($columnValues) && $this->hasColumnTitles($this->data)) {
                    foreach ($this->data['posts_list_column_titles'] as $colIndex => $column) {
                        $sanitizedTitle = sanitize_title($column->column_header);

                        if ($this->arrayDepth($columnValues) > 1) {
                            $accordion[$index]['column_values'][$colIndex] = $columnValues[$index][$sanitizedTitle] ?? '';
                        } else {
                            $accordion[$index]['column_values'][$colIndex] = $columnValues[$sanitizedTitle] ?? '';
                        }
                    }
                }
                $accordion[$index]['heading'] = $item->postTitle ?? '';
                $accordion[$index]['content'] = $item->postContentFiltered ?? '';
            }
        }

        if ($accordion < 0) {
            return null;
        }
        return $accordion;
    }

    /**
     * Get array dimension depth
     * 
     * @param array $colArray
     * 
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

    /**
     * Check if column values are present.
     *
     * @param array $columnValues
     *
     * @return bool
     */
    private function hasColumnValues(array $columnValues): bool
    {
        return isset($columnValues)
            && !empty($columnValues);
    }

    /**
     * Check if column titles are present.
     *
     * @param array $this->data
     *
     * @return bool
     */
    private function hasColumnTitles(): bool
    {
        return !empty($this->data['posts_list_column_titles'])
            && is_array($this->data['posts_list_column_titles']);
    }
}
