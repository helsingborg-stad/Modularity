<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_5805e5dc0a3be',
    'title' => __('Contacts', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_5805e5dc1dc55',
            'label' => __('Kontakter', 'modularity'),
            'name' => 'contacts',
            'aria-label' => '',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layouts' => array(
                '5757b95767730' => array(
                    'key' => '5757b95767730',
                    'name' => 'custom',
                    'label' => __('Custom', 'modularity'),
                    'display' => 'block',
                    'sub_fields' => array(
                        0 => array(
                            'key' => 'field_5805e5dc26dde',
                            'label' => __('Bild', 'modularity'),
                            'name' => 'image',
                            'aria-label' => '',
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
                            'uploader' => '',
                            'acfe_thumbnail' => 0,
                        ),
                        1 => array(
                            'key' => 'field_5805e5dc27255',
                            'label' => __('First name', 'modularity'),
                            'name' => 'first_name',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
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
                        ),
                        2 => array(
                            'key' => 'field_5805e5dc276e1',
                            'label' => __('Efternamn', 'modularity'),
                            'name' => 'last_name',
                            'aria-label' => '',
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
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        3 => array(
                            'key' => 'field_5805e5dc2771c',
                            'label' => __('Jobbtitel', 'modularity'),
                            'name' => 'work_title',
                            'aria-label' => '',
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
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        4 => array(
                            'key' => 'field_5805e5dc277e3',
                            'label' => __('Förvaltning', 'modularity'),
                            'name' => 'administration_unit',
                            'aria-label' => '',
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
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        5 => array(
                            'key' => 'field_5805e5dc27b58',
                            'label' => __('E-mail', 'modularity'),
                            'name' => 'email',
                            'aria-label' => '',
                            'type' => 'email',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '100',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        6 => array(
                            'key' => 'field_5805e62f94d0f',
                            'label' => __('Telefon', 'modularity'),
                            'name' => 'phone_numbers',
                            'aria-label' => '',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'acfe_repeater_stylised_button' => 0,
                            'layout' => 'table',
                            'min' => 0,
                            'max' => 2,
                            'collapsed' => '',
                            'button_label' => __('Lägg till nummer', 'modularity'),
                            'rows_per_page' => 20,
                            'sub_fields' => array(
                                0 => array(
                                    'key' => 'field_5bf6aa828136b',
                                    'label' => __('Typ', 'modularity'),
                                    'name' => 'type',
                                    'aria-label' => '',
                                    'type' => 'select',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '20',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'choices' => array(
                                        'phone' => __('Fasttelefon', 'modularity'),
                                        'smartphone' => __('Mobil', 'modularity'),
                                    ),
                                    'default_value' => __('phone', 'modularity'),
                                    'allow_null' => 0,
                                    'multiple' => 0,
                                    'ui' => 0,
                                    'return_format' => 'value',
                                    'ajax' => 0,
                                    'placeholder' => '',
                                    'allow_custom' => 0,
                                    'search_placeholder' => '',
                                    'parent_repeater' => 'field_5805e62f94d0f',
                                ),
                                1 => array(
                                    'key' => 'field_5805e64a94d10',
                                    'label' => __('Telefonnummer', 'modularity'),
                                    'name' => 'number',
                                    'aria-label' => '',
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
                                    'parent_repeater' => 'field_5805e62f94d0f',
                                ),
                            ),
                        ),
                        7 => array(
                            'key' => 'field_5bf6a5fbc1b6b',
                            'label' => __('Social Media', 'modularity'),
                            'name' => 'social_media',
                            'aria-label' => '',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => '',
                            'min' => 0,
                            'max' => 0,
                            'layout' => 'table',
                            'button_label' => 'Lägg till rad',
                            'rows_per_page' => 20,
                            'acfe_repeater_stylised_button' => 0,
                            'sub_fields' => array(
                                0 => array(
                                    'key' => 'field_5bf6a737c1b6c',
                                    'label' => __('Media', 'modularity'),
                                    'name' => 'media',
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
                                        'facebook' => __('Facebook', 'modularity'),
                                        'linkedin' => __('LinkedIn', 'modularity'),
                                        'twitter' => __('Twitter', 'modularity'),
                                        'instagram' => __('Instagram', 'modularity'),
                                    ),
                                    'default_value' => __('facebook', 'modularity'),
                                    'allow_null' => 0,
                                    'multiple' => 0,
                                    'ui' => 0,
                                    'return_format' => 'value',
                                    'ajax' => 0,
                                    'placeholder' => '',
                                    'allow_custom' => 0,
                                    'search_placeholder' => '',
                                    'parent_repeater' => 'field_5bf6a5fbc1b6b',
                                ),
                                1 => array(
                                    'key' => 'field_5bf6a991c1b6d',
                                    'label' => __('URL', 'modularity'),
                                    'name' => 'url',
                                    'aria-label' => '',
                                    'type' => 'url',
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
                                    'parent_repeater' => 'field_5bf6a5fbc1b6b',
                                ),
                            ),
                        ),
                        8 => array(
                            'key' => 'field_5805e5dc28d3a',
                            'label' => __('Address', 'modularity'),
                            'name' => 'address',
                            'aria-label' => '',
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
                            'placeholder' => '',
                            'maxlength' => '',
                            'rows' => '',
                            'new_lines' => 'wpautop',
                            'acfe_textarea_code' => 0,
                        ),
                        9 => array(
                            'key' => 'field_5805e5dc28e30',
                            'label' => __('Besöksadress', 'modularity'),
                            'name' => 'visiting_address',
                            'aria-label' => '',
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
                            'placeholder' => '',
                            'maxlength' => '',
                            'rows' => '',
                            'new_lines' => 'wpautop',
                            'acfe_textarea_code' => 0,
                        ),
                        10 => array(
                            'key' => 'field_5805e5dc29114',
                            'label' => __('Öppettider', 'modularity'),
                            'name' => 'opening_hours',
                            'aria-label' => '',
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
                            'new_lines' => 'wpautop',
                            'acfe_textarea_code' => 0,
                        ),
                        11 => array(
                            'key' => 'field_5805e5dc29182',
                            'label' => __('Annat', 'modularity'),
                            'name' => 'other',
                            'aria-label' => '',
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
                            'media_upload' => 1,
                            'delay' => 0,
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                    'acfe_flexible_render_template' => false,
                    'acfe_flexible_render_style' => false,
                    'acfe_flexible_render_script' => false,
                    'acfe_flexible_thumbnail' => false,
                    'acfe_flexible_settings' => false,
                    'acfe_flexible_settings_size' => 'medium',
                    'acfe_flexible_modal_edit_size' => false,
                    'acfe_flexible_category' => false,
                ),
                '5757b97ffecc6' => array(
                    'key' => '5757b97ffecc6',
                    'name' => 'user',
                    'label' => __('Användare', 'modularity'),
                    'display' => 'block',
                    'sub_fields' => array(
                        0 => array(
                            'key' => 'field_5805e5dc291c4',
                            'label' => __('Välj användare', 'modularity'),
                            'name' => 'user',
                            'aria-label' => '',
                            'type' => 'user',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'role' => '',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'return_format' => 'array',
                            'bidirectional_target' => array(
                            ),
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                    'acfe_flexible_render_template' => false,
                    'acfe_flexible_render_style' => false,
                    'acfe_flexible_render_script' => false,
                    'acfe_flexible_thumbnail' => false,
                    'acfe_flexible_settings' => false,
                    'acfe_flexible_settings_size' => 'medium',
                    'acfe_flexible_modal_edit_size' => false,
                    'acfe_flexible_category' => false,
                ),
            ),
            'button_label' => __('Lägg till kontakt', 'modularity'),
            'min' => 1,
            'max' => '',
            'acfe_flexible_advanced' => false,
            'acfe_flexible_stylised_button' => false,
            'acfe_flexible_hide_empty_message' => false,
            'acfe_flexible_empty_message' => '',
            'acfe_flexible_layouts_templates' => false,
            'acfe_flexible_layouts_previews' => false,
            'acfe_flexible_layouts_placeholder' => false,
            'acfe_flexible_layouts_thumbnails' => false,
            'acfe_flexible_layouts_settings' => false,
            'acfe_flexible_async' => array(
            ),
            'acfe_flexible_add_actions' => array(
            ),
            'acfe_flexible_remove_button' => array(
            ),
            'acfe_flexible_layouts_state' => false,
            'acfe_flexible_modal_edit' => array(
                'acfe_flexible_modal_edit_enabled' => false,
                'acfe_flexible_modal_edit_size' => 'large',
            ),
            'acfe_flexible_modal' => array(
                'acfe_flexible_modal_enabled' => false,
                'acfe_flexible_modal_title' => false,
                'acfe_flexible_modal_size' => 'full',
                'acfe_flexible_modal_col' => '4',
                'acfe_flexible_modal_categories' => false,
            ),
        ),
        1 => array(
            'key' => 'field_5805e5dc1ddcd',
            'label' => __('Kolumner', 'modularity'),
            'name' => 'columns',
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
                'grid-md-12' => __('1', 'modularity'),
                'grid-md-6' => __('2', 'modularity'),
                'grid-md-4' => __('3', 'modularity'),
                'grid-md-3' => __('4', 'modularity'),
            ),
            'default_value' => __('o-grid-12@md', 'modularity'),
            'return_format' => 'value',
            'multiple' => 0,
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
                'value' => 'mod-contacts',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contacts',
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