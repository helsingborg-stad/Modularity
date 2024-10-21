<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_571dfaabc3fc5',
    'title' => __('Data source', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_571dfaafe6984',
            'label' => __('Data source', 'modularity'),
            'name' => 'posts_data_source',
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
                'posttype' => __('Posttyper', 'modularity'),
                'children' => __('Child posts', 'modularity'),
                'manual' => __('Manually picked posts', 'modularity'),
                'schematype' => __('Schema type', 'modularity'),
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
        1 => array(
            'key' => 'field_670fb7fc4b05c',
            'label' => __('Schema Type', 'modularity'),
            'name' => 'posts_data_schema_type',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'schematype',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => 'modularity-latest-post-type',
                'id' => 'modularity-latest-post-type',
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
        2 => array(
            'key' => 'field_571dfc40f8114',
            'label' => __('Post Types', 'modularity'),
            'name' => 'posts_data_post_type',
            'aria-label' => '',
            'type' => 'posttype_select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'posttype',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => 'modularity-latest-post-type',
                'id' => 'modularity-latest-post-type',
            ),
            'default_value' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'placeholder' => '',
            'disabled' => 0,
            'readonly' => 0,
        ),
        3 => array(
            'key' => 'field_571dfc6ff8115',
            'label' => __('Pick posts to display', 'modularity'),
            'name' => 'posts_data_posts',
            'aria-label' => '',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'manual',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
            ),
            'taxonomy' => array(
            ),
            'allow_null' => 0,
            'multiple' => 1,
            'return_format' => 'id',
            'ui' => 1,
            'save_custom' => 0,
            'save_post_type' => '',
            'save_post_status' => '',
            'bidirectional_target' => array(
            ),
        ),
        4 => array(
            'key' => 'field_571dfcd6b5cf9',
            'label' => __('Childs of', 'modularity'),
            'name' => 'posts_data_child_of',
            'aria-label' => '',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'children',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
            ),
            'taxonomy' => array(
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'id',
            'ui' => 1,
            'save_custom' => 0,
            'save_post_type' => '',
            'save_post_status' => '',
            'bidirectional_target' => array(
            ),
        ),
        5 => array(
            'key' => 'field_571dff4eb46c3',
            'label' => __('Number of posts', 'modularity'),
            'name' => 'posts_count',
            'aria-label' => '',
            'type' => 'number',
            'instructions' => __('Set to -1 to show all', 'modularity'),
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => -1,
            'min' => '',
            'max' => '',
            'step' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'readonly' => 0,
            'disabled' => 0,
        ),
        6 => array(
            'key' => 'field_57ecf1007b749',
            'label' => __('Link to post type archive', 'modularity'),
            'name' => 'archive_link',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'posttype',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'message' => __('Yes, link to post type archive', 'modularity'),
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        7 => array(
            'key' => 'field_6710ff6562e8c',
            'label' => __('From network sites', 'modularity'),
            'name' => 'posts_data_network_sources',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => __('Get posts from other sites in the network. Leave empty to only show posts from this site. If you chose one or more from this list you must also choose this site to get posts from this site.', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'posttype',
                    ),
                ),
                1 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'schematype',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
            ),
            'default_value' => array(
            ),
            'return_format' => 'value',
            'multiple' => 1,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
        8 => array(
            'key' => 'field_6710ff6562e8c',
            'label' => __('From network sites', 'modularity'),
            'name' => 'posts_data_network_sources',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => __('Get posts from other sites in the network. Leave empty to only show posts from this site. If you chose one or more from this list you must also choose this site to get posts from this site.', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'posttype',
                    ),
                ),
                1 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'schematype',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
            ),
            'default_value' => array(
            ),
            'return_format' => 'value',
            'multiple' => 1,
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
    'menu_order' => 1,
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