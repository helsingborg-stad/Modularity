<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_56c47016ea9d5',
    'title' => 'Iframe settings',
    'fields' => array(
        0 => array(
            'default_value' => '',
            'placeholder' => __('Enter your embed url', 'modularity'),
            'key' => 'field_56c4701d32cb4',
            'label' => __('Iframe URL', 'modularity'),
            'name' => 'iframe_url',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => 80,
                'class' => '',
                'id' => '',
            ),
        ),
        1 => array(
            'default_value' => 350,
            'min' => 100,
            'max' => 10000,
            'step' => 10,
            'placeholder' => '',
            'prepend' => '',
            'append' => __('pixels', 'modularity'),
            'key' => 'field_56c4704f32cb5',
            'label' => __('Iframe height', 'modularity'),
            'name' => 'iframe_height',
            'type' => 'number',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => 20,
                'class' => '',
                'id' => '',
            ),
            'readonly' => 0,
            'disabled' => 0,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-iframe',
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
    'modified' => 1455714456,
    'local' => 'json',
));
}