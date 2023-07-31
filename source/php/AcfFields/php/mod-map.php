<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_602400d904b59',
    'title' => __('Map', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_64ad64dacdb16',
            'label' => __('Map type', 'modularity'),
            'name' => 'map_type',
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'default' => __('Default', 'modularity'),
                'openStreetMap' => __('OpenStreetMap', 'modularity'),
            ),
            'default_value' => __('default', 'modularity'),
            'return_format' => 'value',
            'allow_null' => 0,
            'other_choice' => 0,
            'save_other_choice' => 0,
            'layout' => 'vertical',
        ),
        1 => array(
            'key' => 'field_602400dda5195',
            'label' => __('Map URL', 'modularity'),
            'name' => 'map_url',
            'type' => 'url',
            'instructions' => __('<span style="color: #f00;">Din länk måste starta med http<strong>s</strong>:// för att kunna visas på webbplatsen.</span>', 'modularity'),
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
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
        2 => array(
            'key' => 'field_602415a77f5da',
            'label' => __('Description', 'modularity'),
            'name' => 'map_description',
            'type' => 'textarea',
            'instructions' => __('Describe the contents of this map (not shown)', 'modularity'),
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '70',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'acfe_textarea_code' => 0,
            'maxlength' => '',
            'rows' => 3,
            'placeholder' => '',
            'new_lines' => '',
        ),
        3 => array(
            'key' => 'field_602401605c3f6',
            'label' => __('Miniumum Height', 'modularity'),
            'name' => 'height',
            'type' => 'range',
            'instructions' => __('Set a minumum height', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '30',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 400,
            'min' => 256,
            'max' => 720,
            'step' => 16,
            'prepend' => '',
            'append' => __('px', 'modularity'),
        ),
        4 => array(
            'key' => 'field_602459632b135',
            'label' => __('Show button', 'modularity'),
            'name' => 'show_button',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
            'ui' => 1,
        ),
        5 => array(
            'key' => 'field_602459d52b136',
            'label' => __('Button label', 'modularity'),
            'name' => 'button_label',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_602459632b135',
                        'operator' => '==',
                        'value' => '1',
                    ),
                    1 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '30',
                'class' => '',
                'id' => '',
            ),
            'default_value' => __('Show large version', 'modularity'),
            'maxlength' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
        ),
        6 => array(
            'key' => 'field_602459ee2b137',
            'label' => __('Button url', 'modularity'),
            'name' => 'button_url',
            'type' => 'url',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_602459632b135',
                        'operator' => '==',
                        'value' => '1',
                    ),
                    1 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '70',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        7 => array(
            'key' => 'field_60520ab40ffd7',
            'label' => __('Extra info', 'modularity'),
            'name' => 'more_info_button',
            'type' => 'true_false',
            'instructions' => __('Do you want to add Extra info?
The information will be placed in a card on the right side of the map.', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => __('The extra info card is disabled by default', 'modularity'),
            'default_value' => 0,
            'ui_on_text' => __('Enabled', 'modularity'),
            'ui_off_text' => __('Disabled', 'modularity'),
            'ui' => 1,
        ),
        8 => array(
            'key' => 'field_6052101f0ad06',
            'label' => __('Extra info title', 'modularity'),
            'name' => 'more_info_title',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_60520ab40ffd7',
                        'operator' => '==',
                        'value' => '1',
                    ),
                    1 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
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
        ),
        9 => array(
            'key' => 'field_6052087c0ffd6',
            'label' => __('Extra information', 'modularity'),
            'name' => 'more_info',
            'type' => 'wysiwyg',
            'instructions' => __('Add extra information such as opening hours or map location.', 'modularity'),
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_60520ab40ffd7',
                        'operator' => '==',
                        'value' => '1',
                    ),
                    1 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'default',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'delay' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 1,
        ),
        10 => array(
            'key' => 'field_64c77830c32aa',
            'label' => __('Start position', 'modularity'),
            'name' => 'osm_start_position',
            'type' => 'google_map',
            'instructions' => __('Sets the start position of the map', 'modularity'),
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'openStreetMap',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'center_lat' => '',
            'center_lng' => '',
            'zoom' => '',
            'height' => '',
        ),
        11 => array(
            'key' => 'field_64ad5fd710885',
            'label' => __('Map markers', 'modularity'),
            'name' => 'osm_markers',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_64ad64dacdb16',
                        'operator' => '==',
                        'value' => 'openStreetMap',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'acfe_repeater_stylised_button' => 0,
            'layout' => 'block',
            'pagination' => 0,
            'min' => 0,
            'max' => 0,
            'collapsed' => '',
            'button_label' => __('Lägg till rad', 'modularity'),
            'rows_per_page' => 20,
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_64ad60190d5a5',
                    'label' => __('Title', 'modularity'),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'parent_repeater' => 'field_64ad5fd710885',
                ),
                1 => array(
                    'key' => 'field_64ad60b00d5a6',
                    'label' => __('Description', 'modularity'),
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'acfe_textarea_code' => 0,
                    'maxlength' => '',
                    'rows' => '',
                    'placeholder' => '',
                    'new_lines' => '',
                    'parent_repeater' => 'field_64ad5fd710885',
                ),
                2 => array(
                    'key' => 'field_64ad60ed0d5a8',
                    'label' => __('Link text', 'modularity'),
                    'name' => 'link_text',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'parent_repeater' => 'field_64ad5fd710885',
                ),
                3 => array(
                    'key' => 'field_64ad60bd0d5a7',
                    'label' => __('url', 'modularity'),
                    'name' => 'url',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'parent_repeater' => 'field_64ad5fd710885',
                ),
                4 => array(
                    'key' => 'field_64ad61220d5a9',
                    'label' => __('Position', 'modularity'),
                    'name' => 'position',
                    'type' => 'google_map',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'center_lat' => '',
                    'center_lng' => '',
                    'zoom' => '',
                    'height' => '',
                    'parent_repeater' => 'field_64ad5fd710885',
                ),
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-map',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/map',
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