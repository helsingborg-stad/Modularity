<?php

namespace Modularity\Module\Posts\TemplateController;

use Modularity\Module\Posts\Helper\Column as ColumnHelper;
use Modularity\Module\Posts\Helper\Tag as TagHelper;

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
        $this->data['slider']['showStepper']   = isset($fields->show_stepper) ? (bool) $fields->show_stepper: false;
        $this->data['slider']['repeatSlide']   = isset($fields->repeat_slide) ? (bool) $fields->repeat_slide: true;
        $this->data['postsDisplayAs'] = $fields->posts_display_as;

        $this->data['postsDisplayAs']           = $fields->posts_display_as;

        if ($this->data['posts_data_post_type'] === 'project') {
            $this->data['postsDisplayAs'] = 'project';
        }
        
        $this->data['slider'] = apply_filters(
            'Modularity/Module/Posts/Slider/Arguments',
            (object) $this->data['slider']
        );
                
        $this->data['classes'] = implode(
            ' ',
            apply_filters(
                'Modularity/Module/Classes',
                [],
                $this->module->post_type,
                $this->args
            )
        );

        $this->prepare($fields);
    }


    public function prepare($fields)
    {
        $postNum = 0;

        /* Image size */
        $imageDimensions = [1200, 900];

        if (!$fields->posts_alter_columns) {
            $imageDimensions = $this->getImageDimensions(
                $fields->posts_columns,
                [900, 675]
            );
        }

        foreach ($this->data['posts'] as $post) {
            $postNum++;

            /* Image */
            $post->thumbnail = $this->getPostImage(
                $post,
                $this->data['posts_data_source'],
                $imageDimensions,
                $fields->ratio ?? '4:3'
            );

            // Get link for card, or tags
            $post->link = $this->data['posts_data_source'] === 'input' ? $post->permalink : get_permalink($post->ID);

            $this->setPostFlags($post);
        }
    }
}
