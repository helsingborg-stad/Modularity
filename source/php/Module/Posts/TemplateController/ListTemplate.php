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
}
