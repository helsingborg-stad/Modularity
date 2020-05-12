<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_571dffc63090c',
    'title' => 'Data sorting',
    'fields' => array(
        0 => array(
            'key' => 'field_571dffca1d90b',
            'label' => __('Sort by', 'modularity'),
            'name' => 'posts_sort_by',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => 50,
                'class' => '',
                'id' => 'modularity-sorted-by',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
                'false' => __('Do not sort', 'modularity'),
                'ID' => __('ID', 'modularity'),
                'author' => __('Författare', 'modularity'),
                'title' => __('Titel', 'modularity'),
                'date' => __('Publiceringsdatum', 'modularity'),
                'modified' => __('Date modified', 'modularity'),
                'parent' => __('Parent', 'modularity'),
                'rand' => __('Random', 'modularity'),
                'comment_count' => __('Comment count', 'modularity'),
                'menu_order' => __('Menu order', 'modularity'),
            ),
            'default_value' => array(
                0 => 'date',
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        1 => array(
            'key' => 'field_571e00241d90c',
            'label' => __('Order', 'modularity'),
            'name' => 'posts_sort_order',
            'type' => 'radio',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => 50,
                'class' => '',
                'id' => '',
            ),
            'layout' => 'horizontal',
            'choices' => array(
                'asc' => __('Ascending', 'modularity'),
                'desc' => __('Descending', 'modularity'),
            ),
            'default_value' => '',
            'other_choice' => 0,
            'save_other_choice' => 0,
            'allow_null' => 0,
            'return_format' => 'value',
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
    'menu_order' => 10,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
    'modified' => 1461661083,
));
}