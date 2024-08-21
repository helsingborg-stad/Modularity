<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_66c34c64b8d10',
    'title' => __('Menu Module', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_66c59ae797a04',
            'label' => __('Display Menu As', 'modularity'),
            'name' => 'mod_menu_display',
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
                'row-66c59af297a05' => array(
                    'image-select-repeater-label' => 'List',
                    'image-select-repeater-value' => 'list',
                ),
            ),
        ),
        1 => array(
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