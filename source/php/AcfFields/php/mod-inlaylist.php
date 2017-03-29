<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_569e054a7f9c2',
    'title' => 'List',
    'fields' => array(
        0 => array(
            'sub_fields' => array(
                0 => array(
                    'layout' => 'horizontal',
                    'choices' => array(
                        'internal' => __('Internal link', 'modularity'),
                        'external' => __('External link', 'modularity'),
                    ),
                    'default_value' => 'internal',
                    'other_choice' => 0,
                    'save_other_choice' => 0,
                    'allow_null' => 0,
                    'return_format' => 'value',
                    'key' => 'field_569e068b33f31',
                    'label' => __('Link type', 'modularity'),
                    'name' => 'type',
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
                1 => array(
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'key' => 'field_569e0567eb085',
                    'label' => __('Titel', 'modularity'),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => __('If empty, title will default to the linked post\'s/page\'s title', 'modularity'),
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_569e068b33f31',
                                'operator' => '==',
                                'value' => 'internal',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'readonly' => 0,
                    'disabled' => 0,
                ),
                2 => array(
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'key' => 'field_569e0e48a1971',
                    'label' => __('Titel', 'modularity'),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_569e068b33f31',
                                'operator' => '==',
                                'value' => 'external',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'readonly' => 0,
                    'disabled' => 0,
                ),
                3 => array(
                    'post_type' => array(
                        0 => 'post',
                        1 => 'page',
                    ),
                    'taxonomy' => array(
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'return_format' => 'object',
                    'ui' => 1,
                    'key' => 'field_569e05bceb086',
                    'label' => __('Link', 'modularity'),
                    'name' => 'link_internal',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_569e068b33f31',
                                'operator' => '==',
                                'value' => 'internal',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                4 => array(
                    'default_value' => 0,
                    'message' => __('Show publish date', 'modularity'),
                    'ui' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                    'key' => 'field_569e05f8eb087',
                    'label' => __('Date', 'modularity'),
                    'name' => 'date',
                    'type' => 'true_false',
                    'instructions' => __('If checked, the publish/last modified date of the linked post will be displayed.', 'modularity'),
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_569e068b33f31',
                                'operator' => '==',
                                'value' => 'internal',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                5 => array(
                    'default_value' => '',
                    'placeholder' => '',
                    'key' => 'field_569e06f633f32',
                    'label' => __('Link', 'modularity'),
                    'name' => 'link_external',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_569e068b33f31',
                                'operator' => '==',
                                'value' => 'external',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                ),
            ),
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => __('Lägg till rad', 'modularity'),
            'collapsed' => '',
            'key' => 'field_569e0559eb084',
            'label' => __('Lista', 'modularity'),
            'name' => 'items',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
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
                'value' => 'mod-inlaylist',
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
    'modified' => 1457687271,
    'local' => 'json',
));
}