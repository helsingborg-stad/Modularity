<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;

class NewsTemplate extends AbstractController
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

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['classes'] = apply_filters('Modularity/Module/Classes', array(), $module->post_type, $args);

        if ($fields->posts_highlight_first ?? false) {
            $this->data['highlight_first_column'] = ColumnHelper::getFirstColumnSize($this->data['posts_columns']);
            $this->data['highlight_first_column_as'] = $fields->posts_display_highlighted_as ?? 'block';
        }

        $this->preparePosts();
    }
}
