<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;
use Modularity\Module\Posts\Helper\Tag as TagHelper;

class GridTemplate extends AbstractController
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

        $data['display_reading_time'] = $fields->posts_fields['reading_time'] ?? false;

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio;
        $this->data['classes'] = implode(' ', apply_filters(
            'Modularity/Module/Classes',
            [],
            $this->module->post_type ?? '',
            $this->args
        ));

        if ($fields->posts_highlight_first ?? false) {
            $this->data['highlight_first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
            $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        } else {
            $this->data['highlight_first_column'] = false;
        }

        $this->data['gridSize'] = (int)str_replace('-', '', filter_var($fields->posts_columns, FILTER_SANITIZE_NUMBER_INT));
        $this->data['column_width'] = 'o-grid-' . $this->data['gridSize'] . '@md';

        $this->prepare($fields);
    }

    public function prepare($fields)
    {
        $postNum = 0;

        foreach ($this->data['posts'] as $post) {
            $postNum++;
            $this->setPostFlags($post);
        }
    }
}
