<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_64ff22b117e2c',
    'title' => __('Manual Input Data', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_64ff23d0d91bf',
            'label' => __('Display as', 'modularity'),
            'name' => 'display_as',
            'type' => 'radio',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'list' => __('List', 'modularity'),
                'accordion' => __('Accordion', 'modularity'),
                'card' => __('Card', 'modularity'),
                'block' => __('Block', 'modularity'),
                'box' => __('Box', 'modularity'),
                'segment' => __('Segment', 'modularity'),
                'collection' => __('Collection', 'modularity'),
            ),
            'default_value' => __('card', 'modularity'),
            'return_format' => 'value',
            'allow_null' => 0,
            'other_choice' => 0,
            'layout' => 'horizontal',
            'save_other_choice' => 0,
        ),
        1 => array(
            'key' => 'field_650067ed6cc3c',
            'label' => __('Column marking', 'modularity'),
            'name' => 'accordion_column_marking',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ff23d0d91bf',
                        'operator' => '==',
                        'value' => 'accordion',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'maxlength' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
        ),
        2 => array(
            'key' => 'field_65005968bbc75',
            'label' => __('Column titles', 'modularity'),
            'name' => 'accordion_column_titles',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ff23d0d91bf',
                        'operator' => '==',
                        'value' => 'accordion',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'acfe_repeater_stylised_button' => 0,
            'layout' => 'table',
            'pagination' => 0,
            'min' => 0,
            'max' => 4,
            'collapsed' => '',
            'button_label' => __('Lägg till rad', 'modularity'),
            'rows_per_page' => 20,
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_65005a33bbc77',
                    'label' => __('Column title', 'modularity'),
                    'name' => 'accordion_column_title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'parent_repeater' => 'field_65005968bbc75',
                ),
            ),
        ),
        3 => array(
            'key' => 'field_65001d039d4c4',
            'label' => __('Columns', 'modularity'),
            'name' => 'columns',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ff23d0d91bf',
                        'operator' => '!=',
                        'value' => 'list',
                    ),
                    1 => array(
                        'field' => 'field_64ff23d0d91bf',
                        'operator' => '!=',
                        'value' => 'accordion',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'o-grid-12' => __('1', 'modularity'),
                'o-grid-6' => __('2', 'modularity'),
                'o-grid-4' => __('3', 'modularity'),
                'o-grid-3' => __('4', 'modularity'),
            ),
            'default_value' => __('o-grid-4', 'modularity'),
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
        ),
        4 => array(
            'key' => 'field_65016a6f0a085',
            'label' => __('Ratio', 'modularity'),
            'name' => 'ratio',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ff23d0d91bf',
                        'operator' => '==',
                        'value' => 'block',
                    ),
                ),
                1 => array(
                    0 => array(
                        'field' => 'field_64ff23d0d91bf',
                        'operator' => '==',
                        'value' => 'box',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                '1:1' => __('1:1', 'modularity'),
                '4:3' => __('4:3', 'modularity'),
                '12:16' => __('12:16', 'modularity'),
            ),
            'default_value' => __('4:3', 'modularity'),
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
        ),
        5 => array(
            'key' => 'field_64ff22b2d91b7',
            'label' => __('Manual inputs', 'modularity'),
            'name' => 'manual_inputs',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'acfe_repeater_stylised_button' => 0,
            'layout' => 'block',
            'pagination' => 0,
            'min' => 0,
            'max' => 0,
            'collapsed' => '',
            'button_label' => __('Lägg till rad', 'modularity'),
            'rows_per_page' => 20,
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_64ff22fdd91b8',
                    'label' => __('Title', 'modularity'),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'parent_repeater' => 'field_64ff22b2d91b7',
                ),
                1 => array(
                    'key' => 'field_64ff2372d91bc',
                    'label' => __('Column values', 'modularity'),
                    'name' => 'accordion_column_values',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_64ff23d0d91bf',
                                'operator' => '==',
                                'value' => 'accordion',
                            ),
                            1 => array(
                                'field' => 'field_65005968bbc75',
                                'operator' => '!=empty',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'acfe_repeater_stylised_button' => 0,
                    'layout' => 'table',
                    'min' => 0,
                    'max' => 4,
                    'collapsed' => '',
                    'button_label' => __('Lägg till rad', 'modularity'),
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        0 => array(
                            'key' => 'field_64ff23afd91bd',
                            'label' => __('Column value', 'modularity'),
                            'name' => 'accordion_column_value',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'parent_repeater' => 'field_64ff2372d91bc',
                        ),
                    ),
                    'parent_repeater' => 'field_64ff22b2d91b7',
                ),
                2 => array(
                    'key' => 'field_64ff231ed91b9',
                    'label' => __('Content', 'modularity'),
                    'name' => 'content',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_64ff23d0d91bf',
                                'operator' => '!=',
                                'value' => 'list',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'acfe_textarea_code' => 0,
                    'maxlength' => '',
                    'rows' => '',
                    'placeholder' => '',
                    'new_lines' => '',
                    'parent_repeater' => 'field_64ff22b2d91b7',
                ),
                3 => array(
                    'key' => 'field_64ff232ad91ba',
                    'label' => __('Link', 'modularity'),
                    'name' => 'link',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'parent_repeater' => 'field_64ff22b2d91b7',
                ),
                4 => array(
                    'key' => 'field_65002bce6d459',
                    'label' => __('Link text', 'modularity'),
                    'name' => 'link_text',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_64ff23d0d91bf',
                                'operator' => '==',
                                'value' => 'segment',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'parent_repeater' => 'field_64ff22b2d91b7',
                ),
                5 => array(
                    'key' => 'field_65002c7b9c6cc',
                    'label' => __('Image before content', 'modularity'),
                    'name' => 'image_before_content',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_64ff23d0d91bf',
                                'operator' => '==',
                                'value' => 'card',
                            ),
                        ),
                        1 => array(
                            0 => array(
                                'field' => 'field_64ff23d0d91bf',
                                'operator' => '==',
                                'value' => 'segment',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'ui' => 0,
                    'parent_repeater' => 'field_64ff22b2d91b7',
                ),
                6 => array(
                    'key' => 'field_64ff2355d91bb',
                    'label' => __('Image', 'modularity'),
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_64ff23d0d91bf',
                                'operator' => '!=',
                                'value' => 'list',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'uploader' => '',
                    'acfe_thumbnail' => 0,
                    'return_format' => 'array',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'parent_repeater' => 'field_64ff22b2d91b7',
                ),
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-manualinput',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/manualinput',
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