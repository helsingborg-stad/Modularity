<?php


if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_56a8b9eddfced',
        'title' => __('Embed', 'modularity'),
        'fields' => array(
            array(
                'key' => 'field_56a8b9f1902a6',
                'label' => __('Embed code', 'modularity'),
                'name' => 'embed_code',
                'type' => 'textarea',
                'instructions' => __('Paste your script code here (with &lt;style&gt; &lt;script&gt; tags if needed).', 'modularity'),
                'required' => 1,
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
                'new_lines' => '',
            ),
            array(
                'key' => 'field_62a05ea1e4147',
                'label' => __('Display as', 'modularity'),
                'name' => 'script_display_as',
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
                    'card' => __('Card', 'modularity'),
                    'floating' => __('Floating', 'modularity'),
                ),
                'allow_null' => 0,
                'other_choice' => 0,
                'default_value' => __('card', 'modularity'),
                'layout' => 'horizontal',
                'return_format' => 'value',
                'save_other_choice' => 0,
            ),
            array(
                'key' => 'field_60097ea2942bb',
                'label' => __('Card Padding', 'modularity'),
                'name' => 'embeded_card_padding',
                'type' => 'range',
                'instructions' => __('Add padding to card (default: 0, no padding)', 'modularity'),
                'required' => 0,
                'conditional_logic' => array(
                    0 => array(
                        0 => array(
                            'field' => 'field_62a05ea1e4147',
                            'operator' => '==',
                            'value' => 'card',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => 0,
                'min' => '',
                'max' => 4,
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_624f3a552ed05',
                'label' => 'Placeholder image',
                'name' => 'embedded_placeholder_image',
                'type' => 'image',
                'instructions' => __('Add a placeholder image to show where scripts cannot be rendered card', 'modularity'),
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            )
        ),
        'location' => array(
            0 => array(
                0 => array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'mod-script',
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
