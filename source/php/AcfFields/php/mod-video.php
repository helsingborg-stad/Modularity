<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_57454ae7b0e9a',
    'title' => __('Video', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_57454c24d44d8',
            'label' => __('Type', 'modularity'),
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
            'choices' => array(
                'embed' => __('Embed by link (YouTube or Vimeo)', 'modularity'),
                'upload' => __('Upload video file', 'modularity'),
            ),
            'default_value' => '',
            'return_format' => 'value',
            'allow_null' => 0,
            'other_choice' => 0,
            'layout' => 'horizontal',
            'save_other_choice' => 0,
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
            'label' => __('Embed Link', 'modularity'),
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
            'label' => __('Placeholder Image', 'modularity'),
            'name' => 'placeholder_image',
            'type' => 'image',
            'instructions' => __('The placeholder image is displayed before the video is played. If an embed link has been used to embed this video, a placeholder will automatically be feted from embedded service. You can still use this field, to replace the image shown.', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'uploader' => '',
            'acfe_thumbnail' => 0,
            'return_format' => 'array',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
            'preview_size' => 'thumbnail',
            'library' => 'all',
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
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/video',
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
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}