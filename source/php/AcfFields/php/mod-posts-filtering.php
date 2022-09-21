<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_571e045dd555d',
    'title' => __('Filtrera data', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_571e046536f0f',
            'label' => __('Filtrera taxomnomi', 'modularity'),
            'name' => 'posts_taxonomy_filter',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => __('Ja, filtrera data baserat på taxonomi', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        1 => array(
            'key' => 'field_571e048136f10',
            'label' => __('Taxonomi', 'modularity'),
            'name' => 'posts_taxonomy_type',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571e046536f0f',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => 'modularity-latest-taxonomy',
                'id' => 'modularity-latest-taxonomy',
            ),
            'choices' => array(
            ),
            'default_value' => false,
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
        2 => array(
            'key' => 'field_571e049636f11',
            'label' => __('Taxonomivärde', 'modularity'),
            'name' => 'posts_taxonomy_value',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571e046536f0f',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => 'modularity-latest-taxonomy-value',
                'id' => 'modularity-latest-taxonomy-value',
            ),
            'choices' => array(
            ),
            'default_value' => false,
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
        3 => array(
            'key' => 'field_571e04a736f12',
            'label' => __('Metafilter', 'modularity'),
            'name' => 'posts_meta_filter',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => __('Ja, filtrera inlägg baserat på inläggsmeta', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        4 => array(
            'key' => 'field_571e04c736f13',
            'label' => __('Metanyckel', 'modularity'),
            'name' => 'posts_meta_key',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571e04a736f12',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => 'modularity-latest-meta-key',
                'id' => 'modularity-latest-meta-key',
            ),
            'choices' => array(
            ),
            'default_value' => false,
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
        5 => array(
            'key' => 'field_571e04da36f14',
            'label' => __('Metavärde', 'modularity'),
            'name' => 'posts_meta_value',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571e04a736f12',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => 50,
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'maxlength' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'readonly' => 0,
            'disabled' => 0,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-posts',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/posts',
            ),
        ),
    ),
    'menu_order' => 15,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}