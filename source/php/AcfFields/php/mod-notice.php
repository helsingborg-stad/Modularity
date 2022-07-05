<?php 


if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group(array(
    'key' => 'group_575a842dd1283',
    'title' => __('Notice settings', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_575a8454ea3b4',
            'label' => __('Typ', 'modularity'),
            'name' => 'notice_type',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'success' => __('Success (green)', 'modularity'),
                'info' => __('Info (black)', 'modularity'),
                'warning' => __('Warning (gul)', 'modularity'),
                'danger' => __('Danger (red)', 'modularity'),
            ),
            'default_value' => array(
                0 => __('success', 'modularity'),
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
        1 => array(
            'key' => 'field_575a8436ea3b3',
            'label' => __('Notis', 'modularity'),
            'name' => 'notice_text',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 1,
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
            'rows' => '',
            'readonly' => 0,
            'disabled' => 0,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-notice',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/notice',
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