<?php

namespace Modularity\Module\Gallery;

class Gallery extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'gallery',
            __('Gallery', 'modularity'),
            __('Galleries', 'modularity'),
            __('Outputs a gallery with images', 'modularity'),
            array(),
            null,
            null,
            true,
            3600*24*7
        );

        $this->acfFields();
    }

    public function acfFields()
    {
        if (function_exists('acf_add_local_field_group')) {
                acf_add_local_field_group(array (
                    'key' => 'group_5666af6d26b7c',
                    'title' => 'Gallery',
                    'fields' => array (
                        array (
                            'key' => 'field_5666af72e3194',
                            'label' => 'Images',
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
