<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_5891b49127038',
    'title' => __('Text options', 'modularity'),
    'fields' => array(
        0 => array(
            'default_value' => 0,
            'message' => __('Yes, hide the box frame', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
            'key' => 'field_5891b6038c120',
            'label' => __('Hide box frame', 'modularity'),
            'name' => 'hide_box_frame',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
        1 => array(
            'layout' => 'horizontal',
            'choices' => array(
                'text-sm' => __('Small', 'modularity'),
                'text-md' => __('Medium (default)', 'modularity'),
                'text-lg' => __('Large', 'modularity'),
                'text-xl' => __('Extra large', 'modularity'),
            ),
            'default_value' => 'text-md',
            'other_choice' => 0,
            'save_other_choice' => 0,
            'allow_null' => 0,
            'return_format' => 'value',
            'key' => 'field_5891b4982999e',
            'label' => __('Font size', 'modularity'),
            'name' => 'font_size',
            'type' => 'radio',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-text',
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
    'modified' => 1485944357,
    'local' => 'php',
));
}