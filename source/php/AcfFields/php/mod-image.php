<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_570770ab8f064',
    'title' => __('Image', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_570770b8e2e61',
            'label' => __('Image', 'modularity'),
            'name' => 'mod_image_image',
            'type' => 'image',
            'instructions' => __('Allowed file types: jpg, png, gif', 'modularity'),
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => 'jpg, png, gif',
        ),
        1 => array(
            'key' => 'field_587604df2975f',
            'label' => __('Image caption', 'modularity'),
            'name' => 'mod_image_caption',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'new_lines' => 'br',
            'maxlength' => '',
            'placeholder' => '',
            'rows' => 4,
        ),
        2 => array(
            'key' => 'field_57077112e2e63',
            'label' => __('Width', 'modularity'),
            'name' => 'mod_image_crop_width',
            'type' => 'number',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5707716fabf17',
                        'operator' => '==',
                        'value' => 'custom',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 1280,
            'min' => 1,
            'max' => '',
            'placeholder' => '',
            'step' => '',
            'prepend' => '',
            'append' => __('pixels', 'modularity'),
        ),
        3 => array(
            'key' => 'field_5707712be2e64',
            'label' => __('Height', 'modularity'),
            'name' => 'mod_image_crop_height',
            'type' => 'number',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5707716fabf17',
                        'operator' => '==',
                        'value' => 'custom',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 720,
            'min' => 1,
            'max' => '',
            'placeholder' => '',
            'step' => '',
            'prepend' => '',
            'append' => __('pixels', 'modularity'),
        ),
        4 => array(
            'key' => 'field_5707716fabf17',
            'label' => __('Image size', 'modularity'),
            'name' => 'mod_image_size',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_570770b8e2e61',
                        'operator' => '!=empty',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'thumbnail' => __('thumbnail', 'modularity'),
                'medium' => __('medium', 'modularity'),
                'medium_large' => __('medium_large', 'modularity'),
                'large' => __('large', 'modularity'),
                '1536x1536' => __('1536x1536', 'modularity'),
                '2048x2048' => __('2048x2048', 'modularity'),
                'custom' => __('Custom', 'modularity'),
            ),
            'default_value' => false,
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
        ),
        5 => array(
            'key' => 'field_577d07c8d72db',
            'label' => __('Link', 'modularity'),
            'name' => 'mod_image_link',
            'type' => 'radio',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layout' => 'horizontal',
            'choices' => array(
                'false' => __('None', 'modularity'),
                'internal' => __('Internal', 'modularity'),
                'external' => __('External', 'modularity'),
            ),
            'default_value' => '',
            'other_choice' => 0,
            'save_other_choice' => 0,
            'allow_null' => 0,
            'return_format' => 'value',
        ),
        6 => array(
            'key' => 'field_577d0810d72dc',
            'label' => __('Link url', 'modularity'),
            'name' => 'mod_image_link_url',
            'type' => 'url',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_577d07c8d72db',
                        'operator' => '==',
                        'value' => 'external',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        7 => array(
            'key' => 'field_577d0840d72dd',
            'label' => __('Link page', 'modularity'),
            'name' => 'mod_image_link_url',
            'type' => 'page_link',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_577d07c8d72db',
                        'operator' => '==',
                        'value' => 'internal',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
            ),
            'taxonomy' => array(
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'allow_archives' => 1,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-image',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'all',
            ),
        ),
        2 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'all',
            ),
        ),
        3 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'all',
            ),
        ),
        4 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'all',
            ),
        ),
        5 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'all',
            ),
        ),
        6 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/image',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}