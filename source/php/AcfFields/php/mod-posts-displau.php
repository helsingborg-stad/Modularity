<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_571dfd3c07a77',
    'title' => 'Data display',
    'fields' => array(
        0 => array(
            'key' => 'field_571dfd4c0d9d9',
            'label' => __('Display as', 'modularity'),
            'name' => 'posts_display_as',
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
                'expandable-list' => __('Expandable List', 'modularity'),
                'items' => __('Post items', 'modularity'),
                'news' => __('News items', 'modularity'),
                'index' => __('Index', 'modularity'),
                'grid' => __('Grid', 'modularity'),
                'circular' => __('Circular', 'modularity'),
                'horizontal' => __('Horizontal', 'modularity'),
            ),
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => 'list',
            'layout' => 'horizontal',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ),
        1 => array(
            'key' => 'field_571dfdf50d9da',
            'label' => __('Columns', 'modularity'),
            'name' => 'posts_columns',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'items',
                    ),
                ),
                1 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'index',
                    ),
                ),
                2 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'grid',
                    ),
                ),
                3 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'circular',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
                'grid-md-12' => __('1', 'modularity'),
                'grid-md-6' => __('2', 'modularity'),
                'grid-md-4' => __('3', 'modularity'),
                'grid-md-3' => __('4', 'modularity'),
            ),
            'default_value' => array(
                0 => 'grid-md-12',
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
        ),
        2 => array(
            'key' => 'field_571e046536f0e',
            'label' => __('Altering grid size', 'modularity'),
            'name' => 'posts_alter_columns',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'grid',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => __('Yes, alter grid size automatically', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        3 => array(
            'key' => 'field_571e01e7f246c',
            'label' => __('Fields', 'modularity'),
            'name' => 'posts_fields',
            'type' => 'checkbox',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '!=',
                        'value' => 'expandable-list',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'date' => __('Show date published', 'modularity'),
                'excerpt' => __('Show excerpt', 'modularity'),
                'title' => __('Show title', 'modularity'),
                'image' => __('Show featured image', 'modularity'),
            ),
            'allow_custom' => 0,
            'default_value' => array(
                0 => 'date',
                1 => 'excerpt',
                2 => 'title',
                3 => 'image',
            ),
            'layout' => 'horizontal',
            'toggle' => 0,
            'return_format' => 'value',
            'save_custom' => 0,
        ),
        4 => array(
            'key' => 'field_591176fff96d6',
            'label' => __('Hide the title column', 'modularity'),
            'name' => 'posts_hide_title_column',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'expandable-list',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => __('Yes, hide the title column', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        5 => array(
            'key' => 'field_57e3bcae3826e',
            'label' => __('Title column label', 'modularity'),
            'name' => 'title_column_label',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'expandable-list',
                    ),
                    1 => array(
                        'field' => 'field_591176fff96d6',
                        'operator' => '!=',
                        'value' => '1',
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
        6 => array(
            'key' => 'field_571f5776592e6',
            'label' => __('List column labels', 'modularity'),
            'name' => 'posts_list_column_titles',
            'type' => 'repeater',
            'instructions' => __('A title field will always be added as the first column. You will need to go to each post in this list to give the values for each column.', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'expandable-list',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => __('Lägg till rad', 'modularity'),
            'collapsed' => '',
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_571f5790592e7',
                    'label' => __('Column header', 'modularity'),
                    'name' => 'column_header',
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
                    'readonly' => 0,
                    'disabled' => 0,
                ),
            ),
        ),
        7 => array(
            'key' => 'field_59197c6dafb31',
            'label' => __('Allow freetext filtering', 'modularity'),
            'name' => 'allow_freetext_filtering',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'expandable-list',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 1,
            'message' => __('Allow freetext filtering', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        8 => array(
            'key' => 'field_5be480e163246',
            'label' => __('Highlight post', 'modularity'),
            'name' => 'posts_highlight',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'horizontal',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => __('Enabled', 'modularity'),
            'ui_off_text' => __('Disabled', 'modularity'),
        ),
        9 => array(
            'key' => 'field_5bdb0d4217e91',
            'label' => __('Date format', 'modularity'),
            'name' => 'posts_date_format',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'horizontal',
                    ),
                    1 => array(
                        'field' => 'field_571e01e7f246c',
                        'operator' => '==',
                        'value' => 'date',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'default' => __('Default timestamp', 'modularity'),
                'readable' => __('Readable timestamp', 'modularity'),
            ),
            'default_value' => array(
                0 => 'default',
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
        10 => array(
            'key' => 'field_5bd8575106176',
            'label' => __('Placeholder image', 'modularity'),
            'name' => 'posts_placeholder',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfd4c0d9d9',
                        'operator' => '==',
                        'value' => 'horizontal',
                    ),
                    1 => array(
                        'field' => 'field_571e01e7f246c',
                        'operator' => '==',
                        'value' => 'image',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
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
    'menu_order' => -10,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
}