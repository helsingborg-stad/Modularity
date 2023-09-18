<?php

namespace Modularity\Module\Posts\TemplateController;

class SliderTemplate extends AbstractController
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

        $postsColumnsInt = !empty($fields->posts_columns) ? 12 / (int) str_replace('grid-md-', " ", $fields->posts_columns) : 1;

        $this->data['slider']['slidesPerPage'] = $postsColumnsInt;
        $this->data['slider']['autoSlide']     = isset($fields->auto_slide) ? (bool) $fields->auto_slide    : false;
        $this->data['slider']['showStepper']   = isset($fields->show_stepper) ? (bool) $fields->show_stepper : false;
        $this->data['slider']['repeatSlide']   = isset($fields->repeat_slide) ? (bool) $fields->repeat_slide : true;
        $this->data['postsDisplayAs']          = !empty($fields->posts_display_as) ? $fields->posts_display_as : 'segment';

        //TODO: Change this when ContentType templates are done
        if ($this->data['posts_data_post_type'] === 'project') {
            $this->data['postsDisplayAs'] = 'project';
        }

        $this->data['slider'] = apply_filters(
            'Modularity/Module/Posts/Slider/Arguments',
            (object) $this->data['slider']
        );

        $this->prepare($fields);
    }


    public function prepare($fields)
    {
        $this->setPostFlags($post);
    }
}
