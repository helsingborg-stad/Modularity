<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_602400d904b59',
    'title' => __('Map', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_602400dda5195',
            'label' => __('Map URL', 'modularity'),
            'name' => 'map_url',
            'type' => 'url',
            'instructions' => __('<span style="color: #f00;">Your map link must start with http<strong>s</strong>://. Links without this prefix will not display.</span>', 'modularity'),
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        1 => array(
            'key' => 'field_602415a77f5da',
            'label' => __('Description', 'modularity'),
            'name' => 'map_description',
            'type' => 'textarea',
            'instructions' => __('Describe the contents of this map (not shown)', 'modularity'),
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '70',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 3,
            'new_lines' => '',
        ),
        2 => array(
            'key' => 'field_602401605c3f6',
            'label' => __('Height', 'modularity'),
            'name' => 'height',
            'type' => 'range',
            'instructions' => __('Set a fixed height', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '30',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 400,
            'min' => 96,
            'max' => 720,
            'step' => 16,
            'prepend' => '',
            'append' => __('px', 'modularity'),
        ),
        3 => array(
            'key' => 'field_602459632b135',
            'label' => __('Show button', 'modularity'),
            'name' => 'show_button',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        4 => array(
            'key' => 'field_602459d52b136',
            'label' => __('Button label', 'modularity'),
            'name' => 'button_label',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_602459632b135',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '30',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 'Show large version',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        5 => array(
            'key' => 'field_602459ee2b137',
            'label' => __('Button url', 'modularity'),
            'name' => 'button_url',
            'type' => 'url',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_602459632b135',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '70',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-map',
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
));
}