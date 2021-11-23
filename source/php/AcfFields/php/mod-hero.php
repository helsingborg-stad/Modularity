<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_614b3f1a751bf',
    'title' => __('Hero', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_614b3f1e6ed4a',
            'label' => __('Byline', 'modularity'),
            'name' => 'mod_hero_byline',
            'type' => 'text',
            'instructions' => __('A clear byline, to say something else.', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'render_type' => '',
            'filter_context' => '',
            'share_option' => 0,
            'default_value' => 'Hero title',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
            'wp_object' => '',
            'is_id' => 0,
        ),
        1 => array(
            'key' => 'field_614b3f5a6ed4b',
            'label' => __('Body', 'modularity'),
            'name' => 'mod_hero_body',
            'type' => 'textarea',
            'instructions' => __('A short and concise text about this site. What\'s it about? Maximum 500 characters.', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'render_type' => '',
            'filter_context' => '',
            'share_option' => 0,
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => 500,
            'rows' => 4,
            'new_lines' => '',
            'wp_object' => '',
        ),
        2 => array(
            'key' => 'field_614b3f786ed4c',
            'label' => __('Background image', 'modularity'),
            'name' => 'mod_hero_background_image',
            'type' => 'focuspoint',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'render_type' => '',
            'filter_context' => '',
            'share_option' => 0,
            'preview_size' => 'large',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
        ),
        3 => array(
            'key' => 'field_614b43a186da4',
            'label' => __('Size', 'modularity'),
            'name' => 'mod_hero_size',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'repeater_choices' => 0,
            'repeater_field' => '',
            'repeater_label_field' => '',
            'repeater_value_field' => '',
            'repeater_post_id' => 0,
            'repeater_display_value' => 0,
            'render_type' => '',
            'filter_context' => '',
            'share_option' => 0,
            'choices' => array(
                'small' => __('Small', 'modularity'),
                'normal' => __('Normal', 'modularity'),
                'large' => __('Large', 'modularity'),
            ),
            'default_value' => 'normal',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-hero',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/hero',
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