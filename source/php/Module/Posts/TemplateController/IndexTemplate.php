<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

class IndexTemplate extends AbstractController
{
    protected $module;
    protected $args;

    public $data = [];

    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data, $fields)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = $this->arrayToObject(
            get_fields($this->module->ID)
        );

        $this->data['posts_columns'] = apply_filters(
            'Modularity/Display/replaceGrid', 
            isset($fields->posts_columns) ? $fields->posts_columns : 1
        );

        if ($fields->posts_highlight_first ?? false) {
            $this->data['highlight_first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
            $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        } else {
            $this->data['highlight_first_column'] = false;
        }

        $this->preparePosts();
    }
}
