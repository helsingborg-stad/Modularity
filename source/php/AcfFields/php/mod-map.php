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
            'key' => 'field_6024010ea5196',
            'label' => __('Show large version URL', 'modularity'),
            'name' => 'map_url_large',
            'type' => 'url',
            'instructions' => __('<span style="color: #f00;">Your map link must start with http<strong>s</strong>://. Links without this prefix will not display.</span>', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        2 => array(
            'key' => 'field_602401605c3f6',
            'label' => __('Height', 'modularity'),
            'name' => 'height',
            'type' => 'range',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
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