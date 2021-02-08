<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_57454ae7b0e9a',
    'title' => __('Video', 'modularity'),
    'fields' => array(
        0 => array(
            'layout' => 'horizontal',
            'choices' => array(
                'embed' => __('Embed (YouTube or Vimeo link)', 'modularity'),
                'upload' => __('Upload video', 'modularity'),
            ),
            'default_value' => '',
            'other_choice' => 0,
            'save_other_choice' => 0,
            'allow_null' => 0,
            'return_format' => 'value',
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
        ),
        1 => array(
            'return_format' => 'array',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => 'mp4',
            'key' => 'field_57454c5ad44db',
            'label' => __('Video: mp4', 'modularity'),
            'name' => 'video_mp4',
            'type' => 'file',
            'instructions' => '',
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
        ),
        2 => array(
            'return_format' => 'array',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => 'webm',
            'key' => 'field_57454c35d44d9',
            'label' => __('Video: webm', 'modularity'),
            'name' => 'video_webm',
            'type' => 'file',
            'instructions' => '',
            'required' => 0,
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
        ),
        3 => array(
            'return_format' => 'array',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => 'ogg',
            'key' => 'field_57454c49d44da',
            'label' => __('Video: ogg', 'modularity'),
            'name' => 'video_ogg',
            'type' => 'file',
            'instructions' => '',
            'required' => 0,
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
        ),
        4 => array(
            'default_value' => '',
            'placeholder' => '',
            'key' => 'field_57454c7ad44dc',
            'label' => __('Embed link', 'modularity'),
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
        ),
        5 => array(
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
            'key' => 'field_57454c91d44dd',
            'label' => __('Placeholder image', 'modularity'),
            'name' => 'placeholder_image',
            'type' => 'image',
            'instructions' => __('The placeholder image will be shown before the video starts playing.', 'modularity'),
            'required' => 1,
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
    'active' => 1,
    'description' => '',
    'modified' => 1464160040,
    'local' => 'php',
));
}