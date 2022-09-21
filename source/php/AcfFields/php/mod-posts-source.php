<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_571dfaabc3fc5',
    'title' => __('Datakälla', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_571dfaafe6984',
            'label' => __('Datakälla', 'modularity'),
            'name' => 'posts_data_source',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
                'posttype' => __('Posttyper', 'modularity'),
                'children' => __('Underordnade inlägg', 'modularity'),
                'manual' => __('Manuellt utvalda inlägg', 'modularity'),
                'input' => __('Manuell inmatning', 'modularity'),
            ),
            'default_value' => false,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'return_format' => 'value',
            'disabled' => 0,
            'readonly' => 0,
        ),
        1 => array(
            'key' => 'field_571dfc40f8114',
            'label' => __('Posttyper', 'modularity'),
            'name' => 'posts_data_post_type',
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
        2 => array(
            'key' => 'field_571dfc6ff8115',
            'label' => __('Välj inlägg att visa', 'modularity'),
            'name' => 'posts_data_posts',
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
        ),
        3 => array(
            'key' => 'field_571dfcd6b5cf9',
            'label' => __('Inlägg som har förälder', 'modularity'),
            'name' => 'posts_data_child_of',
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
        ),
        4 => array(
            'key' => 'field_571dff4eb46c3',
            'label' => __('Antal inlägg', 'modularity'),
            'name' => 'posts_count',
            'type' => 'number',
            'instructions' => __('Ange -1 för att visa alla', 'modularity'),
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '!=',
                        'value' => 'input',
                    ),
                ),
            ),
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
        5 => array(
            'key' => 'field_576258d3110b0',
            'label' => __('Datainmatning', 'modularity'),
            'name' => 'data',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_571dfaafe6984',
                        'operator' => '==',
                        'value' => 'input',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => __('Lägg till', 'modularity'),
            'collapsed' => '',
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_576258f4110b1',
                    'label' => __('Titel', 'modularity'),
                    'name' => 'post_title',
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
                1 => array(
                    'key' => 'field_57625914110b2',
                    'label' => __('Innehåll', 'modularity'),
                    'name' => 'post_content',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 0,
                    'delay' => 0,
                ),
                2 => array(
                    'key' => 'field_576261c3ef10e',
                    'label' => __('Permalänk', 'modularity'),
                    'name' => 'permalink',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                3 => array(
                    'key' => 'field_57625930110b3',
                    'label' => __('Bild', 'modularity'),
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
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
                4 => array(
                    'key' => 'field_62a309f9c59bb',
                    'label' => __('Ikon', 'modularity'),
                    'name' => 'item_icon',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                    ),
                    'default_value' => false,
                    'allow_null' => 1,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                    'allow_custom' => 0,
                    'placeholder' => '',
                    'search_placeholder' => '',
                ),
                5 => array(
                    'key' => 'field_57625a3e188da',
                    'label' => __('Kolumnvärden', 'modularity'),
                    'name' => 'column_values',
                    'type' => 'repeater',
                    'instructions' => __('Kolumnvärden om expanderbar lista är vald.', 'modularity'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => __('Lägg till', 'modularity'),
                    'collapsed' => '',
                    'sub_fields' => array(
                        0 => array(
                            'key' => 'field_57625a67188db',
                            'label' => __('Värde', 'modularity'),
                            'name' => 'value',
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
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                    ),
                ),
            ),
        ),
        6 => array(
            'key' => 'field_57ecf1007b749',
            'label' => __('Länka till posttypens arkiv', 'modularity'),
            'name' => 'archive_link',
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
            'message' => __('Ja, länka till posttypens arkiv', 'modularity'),
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