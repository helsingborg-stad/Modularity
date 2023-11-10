<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class FeaturesGridTemplate
 * @package Modularity\Module\Posts\TemplateController
 */
class FeaturesGridTemplate extends AbstractController
{
    protected $module;
    protected $args;
    public $data = [];

    /**
     * FeaturesGridTemplate constructor.
     * @param \Modularity\Module\Posts\Posts $module
     * @param array $args
     * @param $data
     */
    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, $data, $fields)
    {
        $this->hookName = 'featuresGrid';
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

        $fields = json_decode(json_encode(get_fields($this->module->ID)));

        $this->data['posts_columns'] = apply_filters('Modularity/Display/replaceGrid', $fields->posts_columns);
        $this->data['ratio'] = $fields->ratio;
        $this->preparePosts($fields);
    }

    public function preparePosts()
    {
        foreach ($this->data['posts'] as $post) {
            $this->setPostFlags($post);
        }
    }
}
