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
     * SliderTemplate constructor.
     *
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     */
    public function __construct(\Modularity\Module\Posts\Posts $module)
    {
        parent::__construct($module);

        $this->data['slider']['slidesPerPage'] = $this->getSlidesPerPage();
        $this->data['slider']['autoSlide']     = isset($this->fields['auto_slide']) ? (bool) $this->fields['auto_slide']    : false;
        $this->data['slider']['showStepper']   = isset($this->fields['show_stepper']) ? (bool) $this->fields['show_stepper'] : false;
        $this->data['slider']['repeatSlide']   = isset($this->fields['repeat_slide']) ? (bool) $this->fields['repeat_slide'] : true;
        $this->data['postsDisplayAs']          = !empty($this->fields['posts_display_as']) ? $this->fields['posts_display_as'] : 'segment';

        //TODO: Change this when ContentType templates are done
        if ($this->data['posts_data_post_type'] === 'project') {
            $this->data['postsDisplayAs'] = 'project';
        }

        $this->data['slider'] = apply_filters(
            'Modularity/Module/Posts/Slider/Arguments',
            (object) $this->data['slider']
        );
    }

    private function getSlidesPerPage() {
        return !empty($this->fields['posts_columns']) ?
            12 / (int) str_replace('grid-md-', " ", $this->fields['posts_columns']) : 1;
    }
}
