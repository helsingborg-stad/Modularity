<?php

namespace Modularity\Module;

class Hero extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'hero',
            __("Hero (slider)", 'modularity-plugin'),
            __("Heroes (sliders)", 'modularity-plugin'),
            __("Outputs multiple images or videos in a sliding apperance.", 'modularity-plugin'),
            array()
        );

        add_action('plugins_loaded', array($this,'acfFields')); 
    }

    public function acfFields()
    {
	    
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_566199a104d0a',
                'title' => 'Hero',
                'fields' => array(
                    array(
                        'key' => 'field_56669ca8e12a6',
                        'label' => 'Show title',
                        'name' => 'mod_hero_show_title',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'true' => 'Yes',
                            'false' => 'No',
                        ),
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'false',
                        'layout' => 'horizontal',
                    ),
                    array(
                        'key' => 'field_566199a65c835',
                        'label' => 'Images',
                        'name' => 'mod_hero_images',
                        'type' => 'gallery',
                        'instructions' => 'You can use one or more image. If using more than one image we\'ll instantiate a slider.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'min' => 1,
                        'max' => '',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => 'jpg, png, jpeg',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'mod-hero',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));
        }
    }
}

//TÄNKTE MIG FLICKITY HÄR, MED OPTIONS FÖR DE OLIKA UTSEENDENA
//http://flickity.metafizzy.co/

