<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_56a8c4581d906',
    'title' => __('Sorted Posts', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_56d01a16eb9f3',
            'label' => __('Visa som', 'modularity'),
            'name' => 'view_as',
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
                'list' => __('Lista', 'modularity'),
                'items' => __('Inläggsobjekt', 'modularity'),
                'news' => __('Nyhetsobjekt', 'modularity'),
            ),
            'default_value' => array(
                0 => 0,
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        1 => array(
            'key' => 'field_56a8c474647a6',
            'label' => __('Posttyp', 'modularity'),
            'name' => 'post_type',
            'type' => 'posttype_select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => 50,
                'class' => '',
                'id' => 'modularity-latest-post-type',
            ),
            'default_value' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'placeholder' => '',
            'disabled' => 0,
            'readonly' => 0,
        ),
        2 => array(
            'key' => 'field_56a8c5cc647ac',
            'label' => __('Sortera efter', 'modularity'),
            'name' => 'sorted_by',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => 'modularity-sorted-by',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
                'ID' => __('ID', 'modularity'),
                'post_author' => __('Författare', 'modularity'),
                'post_title' => __('Titel', 'modularity'),
                'post_date' => __('Publiceringsdatum', 'modularity'),
                'post_modified' => __('Senast uppdaterad', 'modularity'),
                'post_parent' => __('Parent', 'modularity'),
                'post_rand' => __('Slumpmässig', 'modularity'),
                'post_comment_count' => __('Antal kommentarer', 'modularity'),
                'post_menu_order' => __('Menyordning', 'modularity'),
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
        3 => array(
            'key' => 'field_56d55ec58b9e3',
            'label' => __('Ordna efter', 'modularity'),
            'name' => 'order',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
                'desc' => __('Fallande', 'modularity'),
                'asc' => __('Stigande', 'modularity'),
            ),
            'default_value' => array(
                0 => 'desc',
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        4 => array(
            'key' => 'field_56a8c593647ab',
            'label' => __('Antal inlägg', 'modularity'),
            'name' => 'number_of_posts',
            'type' => 'number',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 5,
            'min' => '',
            'max' => '',
            'step' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'readonly' => 0,
            'disabled' => 0,
        ),
        5 => array(
            'key' => 'field_56a8c4c5647a7',
            'label' => __('Visa datum', 'modularity'),
            'name' => 'show_date',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => '',
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        6 => array(
            'key' => 'field_56a8c531647a8',
            'label' => __('Visa ingress om möjligt', 'modularity'),
            'name' => 'show_excerpt',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => '',
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        7 => array(
            'key' => 'field_56a8c554647a9',
            'label' => __('Visa inläggets titel', 'modularity'),
            'name' => 'show_title',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => '',
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        8 => array(
            'key' => 'field_56a8c56a647aa',
            'label' => __('Visa utvald bild', 'modularity'),
            'name' => 'show_picture',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => '',
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        9 => array(
            'key' => 'field_57397624ab5ec',
            'label' => __('Visa ”visa mer”-knappen', 'modularity'),
            'name' => 'show_view_more_button',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => '',
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        10 => array(
            'key' => 'field_56c6eebaa1de4',
            'label' => __('Filtrera taxomnomi', 'modularity'),
            'name' => 'taxonomy_filter',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33.333',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => __('Filtrera efter taxonomi', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        11 => array(
            'key' => 'field_56c6eee0a1de5',
            'label' => __('Taxonomi', 'modularity'),
            'name' => 'filter_posts_taxonomy_type',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_56c6eebaa1de4',
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
                'category' => __('category', 'modularity'),
                'post_tag' => __('post_tag', 'modularity'),
                'nav_menu' => __('nav_menu', 'modularity'),
                'link_category' => __('link_category', 'modularity'),
                'post_format' => __('post_format', 'modularity'),
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
        12 => array(
            'key' => 'field_56c6ef3ca1de6',
            'label' => __('Valda värden', 'modularity'),
            'name' => 'filter_posts_by_tag',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_56c6eebaa1de4',
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
            'multiple' => 1,
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
        13 => array(
            'key' => 'field_56eac0e900dab',
            'label' => __('Objektkolumner', 'modularity'),
            'name' => 'item_column_size',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_56d01a16eb9f3',
                        'operator' => '==',
                        'value' => 'items',
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
                'grid-md-12' => 1,
                'grid-md-6' => 2,
                'grid-md-4' => 3,
                'grid-md-3' => 4,
            ),
            'default_value' => array(
                0 => 'grid-md-12',
            ),
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-latest',
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
    'modified' => 1463386320,
));
}