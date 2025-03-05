<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_66c34c64b8d10',
    'title' => __('Menymodul', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_66c59ae797a04',
            'label' => __('Visa meny som', 'modularity'),
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
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => 'acf-hidden',
                'id' => '',
            ),
            'default_value' => '',
            'maxlength' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
        ),
        2 => array(
            'key' => 'field_66c34c655680e',
            'label' => __('Välj meny', 'modularity'),
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
                3 => __('Header-meny', 'modularity'),
                5 => __('Panelmeny', 'modularity'),
                53 => __('Startpuffar', 'modularity'),
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
            'key' => 'field_6734a0413d66b',
            'label' => __('Bakgrund', 'modularity'),
            'name' => 'mod_menu_wrapped',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => __('Bestämmer ifall menyn ska ha en bakgrund', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
            'ui' => 1,
        ),
        4 => array(
            'key' => 'field_678a24781b23e',
            'label' => __('Background Notice', 'modularity'),
            'name' => '',
            'aria-label' => '',
            'type' => 'message',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_6734a0413d66b',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => __('While using a background, the "Item Per Column" works different. 

Amount of parents / columns needs to be a whole number for the background to work. 
If it isn\'t a whole number it will be calculated automatically using a better suited amount of columns using an amount as close to your choice as possible.', 'modularity'),
            'new_lines' => 'wpautop',
            'esc_html' => 0,
        ),
        5 => array(
            'key' => 'field_66c5c3e6b7b5d',
            'label' => __('Antal objekt per rad', 'modularity'),
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
        6 => array(
            'key' => 'field_67c846a5654c1',
            'label' => __('Collapse menu on mobile', 'modularity'),
            'name' => 'mod_menu_mobile_collapse',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => __('Will collapse menu groups on mobile to save vertical space. User has ability to expand individual menu groups with a button click.', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 1,
            'allow_in_bindings' => 1,
            'ui_on_text' => '',
            'ui_off_text' => '',
            'ui' => 1,
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