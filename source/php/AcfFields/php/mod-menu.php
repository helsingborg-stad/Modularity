<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_66c34c64b8d10',
    'title' => __('Menu Module', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_66c59ae797a04',
            'label' => __('Display Menu As', 'modularity'),
            'name' => 'mod_menu_display_as',
            'aria-label' => '',
            'type' => 'image_select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'row-row-row-row-row-row-row-66c59af297a05' => array(
                    'image-select-repeater-label' => 'List',
                    'image-select-repeater-value' => 'listing',
                ),
            ),
        ),
        1 => array(
            'key' => 'field_66c5c58856a24',
            'label' => __('Display as conditional target', 'modularity'),
            'name' => 'mod_menu_display_as_conditional',
            'aria-label' => '',
            'type' => 'acfe_hidden',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
        ),
        2 => array(
            'key' => 'field_66c34c655680e',
            'label' => __('Select a Menu', 'modularity'),
            'name' => 'mod_menu_menu',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                575 => __('drawer down', 'modularity'),
                564 => __('Language', 'modularity'),
                573 => __('ny', 'modularity'),
                576 => __('Primary', 'modularity'),
            ),
            'default_value' => false,
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
        3 => array(
            'key' => 'field_66c5c3e6b7b5d',
            'label' => __('Items Per Column', 'modularity'),
            'name' => 'mod_menu_columns',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_66c5c58856a24',
                        'operator' => '==',
                        'value' => 'listing',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                1 => __('1', 'modularity'),
                2 => __('2', 'modularity'),
                3 => __('3', 'modularity'),
                4 => __('4', 'modularity'),
            ),
            'default_value' => 3,
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-menu',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/menu',
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