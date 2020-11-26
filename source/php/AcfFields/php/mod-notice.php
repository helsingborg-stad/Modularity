<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_575a842dd1283',
    'title' => 'Notice settings',
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
                'success' => __('Framgång (grön)', 'modularity'),
                'info' => __('Info (Svart)', 'modularity'),
                'warning' => __('Varning (gul)', 'modularity'),
                'danger' => __('Fara (röd)', 'modularity'),
            ),
            'default_value' => 'success',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
        1 => array(
            'key' => 'field_575a84bdea3b6',
            'label' => __('Storlek', 'modularity'),
            'name' => 'notice_size',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => 50,
                'class' => '',
                'id' => '',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
                'notice-md' => __('Standard', 'modularity'),
                'notice-lg' => __('Stor', 'modularity'),
                'notice-sm' => __('Liten', 'modularity'),
            ),
            'default_value' => 'notice-md',
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        2 => array(
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