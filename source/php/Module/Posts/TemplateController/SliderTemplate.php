<?php

namespace Modularity\Module\Posts\TemplateController;

/**
 * Class SliderTemplate
 *
 * Template controller for rendering posts as a slider.
 *
 * @package Modularity\Module\Posts\TemplateController
 */
class SliderTemplate extends AbstractController
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
     * Acf fields.
     *
     * @var object
     */
    public $fields;

    /**
     * SliderTemplate constructor.
     *
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     * @param array $args Arguments passed to the template controller.
     * @param array $data Data to be used in rendering the template.
     * @param object $fields Object containing prepared fields for rendering.
     */
    public function __construct(\Modularity\Module\Posts\Posts $module, array $args, array $data, object $fields)
    {
        $this->module = $module;
        $this->args = $args;
        $this->data = $data;

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

        $this->prepareFields($fields);
        $this->preparePosts();
    }
}
