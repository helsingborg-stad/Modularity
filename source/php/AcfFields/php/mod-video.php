<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_57454ae7b0e9a',
    'title' => 'Video',
    'fields' => array(
        0 => array(
            'key' => 'field_57454c24d44d8',
            'label' => __('Typ', 'modularity'),
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
            'layout' => 'horizontal',
            'choices' => array(
                'embed' => __('Bädda in (YouTube eller Vimeo-länk)', 'modularity'),
                'upload' => __('Ladda upp video', 'modularity'),
            ),
            'default_value' => '',
            'other_choice' => 0,
            'save_other_choice' => 0,
            'allow_null' => 0,
            'return_format' => 'value',
        ),
        1 => array(
            'key' => 'field_57454c5ad44db',
            'label' => __('Video: mp4', 'modularity'),
            'name' => 'video_mp4',
            'type' => 'file',
            'instructions' => __('Upload or select file from library. The only valid file format is .mp4', 'modularity'),
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_57454c24d44d8',
                        'operator' => '==',
                        'value' => 'upload',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33.3333',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => 'mp4',
        ),
        2 => array(
            'key' => 'field_57454c7ad44dc',
            'label' => __('Inbäddningslänk', 'modularity'),
            'name' => 'embed_link',
            'type' => 'url',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_57454c24d44d8',
                        'operator' => '==',
                        'value' => 'embed',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        3 => array(
            'key' => 'field_57454c91d44dd',
            'label' => __('Affischbild', 'modularity'),
            'name' => 'placeholder_image',
            'type' => 'image',
            'instructions' => __('Affischbilden visas innan videon sätts igång.', 'modularity'),
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
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-video',
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
));
}