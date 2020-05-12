<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_571e045dd555d',
    'title' => __('Data filtering', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_571e046536f0f',
            'label' => __('Taxonomy filter', 'modularity'),
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
            'message' => __('Yes, filter posts based on taxonomy', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        1 => array(
            'key' => 'field_571e048136f10',
            'label' => __('Taxonomy type', 'modularity'),
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
                'width' => 50,
                'class' => '',
                'id' => 'modularity-latest-taxonomy',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
            ),
            'default_value' => array(
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        2 => array(
            'key' => 'field_571e049636f11',
            'label' => __('Taxonomy value', 'modularity'),
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
                'width' => 50,
                'class' => '',
                'id' => 'modularity-latest-taxonomy-value',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
            ),
            'default_value' => array(
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        3 => array(
            'key' => 'field_571e04a736f12',
            'label' => __('Meta filter', 'modularity'),
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
            'message' => __('Yes, filter posts based on meta', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        4 => array(
            'key' => 'field_571e04c736f13',
            'label' => __('Meta key', 'modularity'),
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
                'width' => 50,
                'class' => '',
                'id' => 'modularity-latest-meta-key',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
            ),
            'default_value' => array(
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        5 => array(
            'key' => 'field_571e04da36f14',
            'label' => __('Meta value', 'modularity'),
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
        6 => array(
            'key' => 'field_5af2f2e486366',
            'label' => __('Front End taxonomy filtering', 'modularity'),
            'name' => 'front_end_tax_filtering',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
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
                'width' => '',
                'class' => 'frontend-filter',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        7 => array(
            'key' => 'field_5af2f64510be1',
            'label' => __('Front End Text search', 'modularity'),
            'name' => 'front_end_tax_filtering_text_search',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5af2f2e486366',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33',
                'class' => 'frontend-filter',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        8 => array(
            'key' => 'field_5af2f68810be2',
            'label' => __('Front End Date range', 'modularity'),
            'name' => 'front_end_tax_filtering_dates',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5af2f2e486366',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33',
                'class' => 'frontend-filter',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        9 => array(
            'key' => 'field_5af2f6fb10be3',
            'label' => __('Front End Taxonomy', 'modularity'),
            'name' => 'front_end_tax_filtering_taxonomy',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5af2f2e486366',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33',
                'class' => 'frontend-filter',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        10 => array(
            'key' => 'field_5b0d0c3f955b6',
            'label' => __('Front End Button text', 'modularity'),
            'name' => 'front_end_button_text',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5af2f2e486366',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33',
                'class' => 'frontend-filter',
                'id' => '',
            ),
            'default_value' => 'Search',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        11 => array(
            'key' => 'field_5b0d103f8dc5a',
            'label' => __('Front End Hide date', 'modularity'),
            'name' => 'front_end_hide_date',
            'type' => 'true_false',
            'instructions' => __('Hide date range options behind a button', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5af2f2e486366',
                        'operator' => '==',
                        'value' => '1',
                    ),
                    1 => array(
                        'field' => 'field_5af2f68810be2',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33',
                'class' => 'frontend-filter',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 1,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        12 => array(
            'key' => 'field_5b0d10e78dc5b',
            'label' => __('Front End Display', 'modularity'),
            'name' => 'front_end_display',
            'type' => 'true_false',
            'instructions' => __('Show all filter options on same row', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5af2f2e486366',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33',
                'class' => 'frontend-filter',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
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
    ),
    'menu_order' => 15,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
}