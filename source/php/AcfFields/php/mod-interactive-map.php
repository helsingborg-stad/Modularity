<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_67a6218f4b8a6',
    'title' => __('Interactive Map', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_67a9b074d4fc5',
            'label' => __('Post type', 'modularity'),
            'name' => 'Interactive_map_post_type',
            'aria-label' => '',
            'type' => 'posttype_select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'placeholder' => '',
            'disabled' => 0,
            'readonly' => 0,
        ),
        1 => array(
            'key' => 'field_67a6219ca1318',
            'label' => __('Start position', 'modularity'),
            'name' => 'interactive_map_start_position',
            'aria-label' => '',
            'type' => 'google_map',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'center_lat' => '59.32932',
            'center_lng' => '18.06858',
            'zoom' => '',
            'height' => 500,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-interactivemap',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/interactivemap',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'left',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => array(
        0 => 'json',
    ),
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}