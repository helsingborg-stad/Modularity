<?php

namespace Modularity\Module;

class Gallery extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'gallery',
            'Gallery',
            'Galleries',
            'Outputs a gallery with images',
            array()
        );

        $this->acfFields();
    }

    public function acfFields()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array (
                'key' => 'group_566698470e7d0',
                'title' => 'Gallery',
                'fields' => array (
                    array(
                        'key' => 'field_56669ca8e12345',
                        'label' => 'Show title',
                        'name' => 'mod_gallery_show_title',
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
                        'default_value' => 'true',
                        'layout' => 'vertical',
                    ),
                    array (
                        'key' => 'field_5666984b52220',
                        'label' => 'Gallery',
                        'name' => 'mod_gallery_images',
                        'type' => 'gallery',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'min' => '',
                        'max' => '',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'mod-gallery',
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
