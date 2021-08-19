<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_56a5e99108991',
    'title' => __('Slider', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_573dce058a66e',
            'label' => __('Slider ratio', 'modularity'),
            'name' => 'slider_format',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '100',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'ratio-36-7' => __('Wider (36:7)', 'modularity'),
                'ratio-10-3' => __('Wide (10:3)', 'modularity'),
                'ratio-16-9' => __('Normal (16:9, video)', 'modularity'),
                'ratio-4-3' => __('Square (4:3)', 'modularity'),
            ),
            'default_value' => 'ratio-16-9',
            'allow_null' => 1,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
        ),
        1 => array(
            'key' => 'field_5731c6d886811',
            'label' => __('Autoslide', 'modularity'),
            'name' => 'slides_autoslide',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33',
                'class' => '',
                'id' => '',
            ),
            'message' => __('Slide automatically between views', 'modularity'),
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        2 => array(
            'key' => 'field_5731c78886813',
            'label' => __('Slide interval', 'modularity'),
            'name' => 'slides_slide_timeout',
            'type' => 'number',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5731c6d886811',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '33',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 5,
            'placeholder' => '',
            'prepend' => '',
            'append' => __('seconds', 'modularity'),
            'min' => 1,
            'max' => 20,
            'step' => 1,
        ),
        3 => array(
            'key' => 'field_58933fb6f5ed4',
            'label' => __('Wrap around', 'modularity'),
            'name' => 'additional_options',
            'type' => 'checkbox',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '33',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'wrapAround' => __('Go to first slide when last item is reached', 'modularity'),
            ),
            'allow_custom' => 0,
            'default_value' => array(
                0 => 'wrapAround',
            ),
            'layout' => 'horizontal',
            'toggle' => 0,
            'return_format' => 'value',
            'save_custom' => 0,
        ),
        4 => array(
            'key' => 'field_56a5e994398d6',
            'label' => __('Slides', 'modularity'),
            'name' => 'slides',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layouts' => array(
                '56a5ed29398db' => array(
                    'key' => '56a5ed29398db',
                    'name' => 'image',
                    'label' => __('Bild', 'modularity'),
                    'display' => 'block',
                    'sub_fields' => array(
                        0 => array(
                            'key' => 'field_56a5ed2f398dc',
                            'label' => 'Desktop image',
                            'name' => 'image',
                            'type' => 'focuspoint',
                            'instructions' => 'This image will be used in larger screens. Preferably a widescreen image.',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '50',
                                'class' => '',
                                'id' => '',
                            ),
                            'preview_size' => 'medium',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                        ),
                        1 => array(
                            'key' => 'field_611e188441250',
                            'label' => 'Alt text',
                            'name' => 'alt_text',
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
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        2 => array(
                            'key' => 'field_56e7fa230ee09',
                            'label' => 'Template',
                            'name' => 'textblock_position',
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
                                'bottom' => 'Bottom banner',
                                'center' => 'Centered',
                                'hero' => 'Hero',
                            ),
                            'default_value' => 'bottom',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        3 => array(
                            'key' => 'field_5fca2b4da3be6',
                            'label' => 'Text color',
                            'name' => 'text_color',
                            'type' => 'select',
                            'instructions' => 'What color to use on this slide',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'black' => 'Black',
                                'white' => 'White',
                                'theme' => 'Inherit theme',
                            ),
                            'default_value' => 'black',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        4 => array(
                            'key' => 'field_5fca2bd1a3be7',
                            'label' => 'Background color text area',
                            'name' => 'background_color',
                            'type' => 'select',
                            'instructions' => 'Select a color for the wrapping area of the text',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'none' => 'No background',
                                'white' => 'White',
                                'theme' => 'Inherit theme',
                                'theme-opacity' => 'Inherit theme, add transparency',
                            ),
                            'default_value' => 'white',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        5 => array(
                            'key' => 'field_5702597b7d869',
                            'label' => 'Title',
                            'name' => 'textblock_title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56e7fa230ee09',
                                        'operator' => '==',
                                        'value' => 'center',
                                    ),
                                ),
                                1 => array(
                                    0 => array(
                                        'field' => 'field_56e7fa230ee09',
                                        'operator' => '==',
                                        'value' => 'bottom',
                                    ),
                                ),
                                2 => array(
                                    0 => array(
                                        'field' => 'field_56e7fa230ee09',
                                        'operator' => '==',
                                        'value' => 'hero',
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
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        6 => array(
                            'key' => 'field_56ab235393f04',
                            'label' => 'Content',
                            'name' => 'textblock_content',
                            'type' => 'textarea',
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
                            'maxlength' => '',
                            'rows' => '',
                            'new_lines' => 'br',
                        ),
                        7 => array(
                            'key' => 'field_56fa82a2d464d',
                            'label' => 'Link',
                            'name' => 'link_type',
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
                                'false' => 'No link',
                                'internal' => 'Internal',
                                'external' => 'External',
                            ),
                            'allow_null' => 0,
                            'other_choice' => 0,
                            'default_value' => 'false',
                            'layout' => 'horizontal',
                            'return_format' => 'value',
                            'save_other_choice' => 0,
                        ),
                        8 => array(
                            'key' => 'field_608915f2b15f7',
                            'label' => 'Link Style',
                            'name' => 'link_style',
                            'type' => 'radio',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56fa82a2d464d',
                                        'operator' => '==',
                                        'value' => 'internal',
                                    ),
                                ),
                                1 => array(
                                    0 => array(
                                        'field' => 'field_56fa82a2d464d',
                                        'operator' => '==',
                                        'value' => 'external',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'cover' => 'Cover slide',
                                'button' => 'Button',
                            ),
                            'allow_null' => 0,
                            'other_choice' => 0,
                            'default_value' => 'cover',
                            'layout' => 'horizontal',
                            'return_format' => 'value',
                            'save_other_choice' => 0,
                        ),
                        9 => array(
                            'key' => 'field_60891647b15f8',
                            'label' => 'Link Text',
                            'name' => 'link_text',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_608915f2b15f7',
                                        'operator' => '==',
                                        'value' => 'button',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'Read More',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        10 => array(
                            'key' => 'field_56fa8313d4650',
                            'label' => 'Url',
                            'name' => 'link_url',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56fa82a2d464d',
                                        'operator' => '==',
                                        'value' => 'external',
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
                        11 => array(
                            'key' => 'field_56fa8331d4651',
                            'label' => 'Page',
                            'name' => 'link_url',
                            'type' => 'page_link',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56fa82a2d464d',
                                        'operator' => '==',
                                        'value' => 'internal',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => '',
                            'taxonomy' => '',
                            'allow_null' => 0,
                            'allow_archives' => 1,
                            'multiple' => 0,
                        ),
                        12 => array(
                            'key' => 'field_60db1cc88b16d',
                            'label' => 'Description',
                            'name' => 'link_url_description',
                            'type' => 'text',
                            'instructions' => 'Describe the purpose of the link, or what visitors can expect to find when they click on it (not shown).',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56fa82a2d464d',
                                        'operator' => '==',
                                        'value' => 'external',
                                    ),
                                ),
                                1 => array(
                                    0 => array(
                                        'field' => 'field_56fa82a2d464d',
                                        'operator' => '==',
                                        'value' => 'internal',
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
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
                '56a5e9a5bbaf1' => array(
                    'key' => '56a5e9a5bbaf1',
                    'name' => 'video',
                    'label' => __('Video', 'modularity'),
                    'display' => 'block',
                    'sub_fields' => array(
                        0 => array(
                            'key' => 'field_56a5eb09398d8',
                            'label' => 'Video: mp4',
                            'name' => 'video_mp4',
                            'type' => 'file',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '50',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'library' => 'all',
                            'min_size' => '',
                            'max_size' => 20,
                            'mime_types' => 'mp4',
                        ),
                        1 => array(
                            'key' => 'field_56b9e2a221291',
                            'label' => 'Placeholder Image',
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '50',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => 1140,
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => 'png,jpg',
                        ),
                        2 => array(
                            'key' => 'field_6007fbb665ed9',
                            'label' => 'Title',
                            'name' => 'textblock_title',
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
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        3 => array(
                            'key' => 'field_56b9f3b8d7720',
                            'label' => 'Content',
                            'name' => 'textblock_content',
                            'type' => 'textarea',
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
                            'maxlength' => '',
                            'rows' => '',
                            'new_lines' => 'br',
                        ),
                        4 => array(
                            'key' => 'field_56e7fa620ee0a',
                            'label' => 'Text position',
                            'name' => 'textblock_position',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56b9f3dba4f22',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                    1 => array(
                                        'field' => 'field_56a5eada398d7',
                                        'operator' => '==',
                                        'value' => 'upload',
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
                                'bottom' => 'Bottom banner',
                                'center' => 'Centered',
                            ),
                            'default_value' => 'bottom',
                            'ui' => 0,
                            'ajax' => 0,
                            'placeholder' => '',
                            'return_format' => 'value',
                            'disabled' => 0,
                            'readonly' => 0,
                        ),
                        5 => array(
                            'key' => 'field_6007f74a5f5a5',
                            'label' => 'Text color',
                            'name' => 'text_color',
                            'type' => 'select',
                            'instructions' => 'What color to use on this slide',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'black' => 'Black',
                                'white' => 'White',
                                'theme' => 'Inherit theme',
                            ),
                            'default_value' => 'black',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        6 => array(
                            'key' => 'field_6007f6bf5f5a3',
                            'label' => 'Background color text area',
                            'name' => 'background_color',
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
                                'none' => 'No background',
                                'white' => 'White',
                                'theme' => 'Inherit theme',
                                'theme-opacity' => 'Inherit theme, add transparency',
                            ),
                            'default_value' => 'white',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        7 => array(
                            'key' => 'field_56fa87ec3ace2',
                            'label' => 'Link',
                            'name' => 'link_type',
                            'type' => 'radio',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56a5eada398d7',
                                        'operator' => '==',
                                        'value' => 'upload',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'false' => 'No link',
                                'internal' => 'Intern',
                                'external' => 'Extern',
                            ),
                            'allow_null' => 0,
                            'other_choice' => 0,
                            'default_value' => 'false',
                            'layout' => 'horizontal',
                            'return_format' => 'value',
                            'save_other_choice' => 0,
                        ),
                        8 => array(
                            'key' => 'field_56fa87fa3ace4',
                            'label' => 'Url',
                            'name' => 'link_url',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56a5eada398d7',
                                        'operator' => '==',
                                        'value' => 'upload',
                                    ),
                                    1 => array(
                                        'field' => 'field_56fa87ec3ace2',
                                        'operator' => '==',
                                        'value' => 'external',
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
                        9 => array(
                            'key' => 'field_56fa88043ace5',
                            'label' => 'Page',
                            'name' => 'link_url',
                            'type' => 'page_link',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56a5eada398d7',
                                        'operator' => '==',
                                        'value' => 'upload',
                                    ),
                                    1 => array(
                                        'field' => 'field_56fa87ec3ace2',
                                        'operator' => '==',
                                        'value' => 'internal',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => '',
                            'taxonomy' => '',
                            'allow_null' => 0,
                            'allow_archives' => 1,
                            'multiple' => 0,
                        ),
                        10 => array(
                            'key' => 'field_60080ae377d79',
                            'label' => 'Link text',
                            'name' => 'link_text',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                0 => array(
                                    0 => array(
                                        'field' => 'field_56fa87ec3ace2',
                                        'operator' => '!=',
                                        'value' => 'false',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'Read more',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => 50,
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
            ),
            'button_label' => __('Add slide', 'modularity'),
            'min' => 1,
            'max' => 6,
        ),
        5 => array(
            'key' => 'field_6093ac63b2bc3',
            'label' => __('Shadow', 'modularity'),
            'name' => 'slider_shadow',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 1,
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
                'value' => 'mod-slider',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        2 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        3 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        4 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        5 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        6 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        7 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        8 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
        9 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
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