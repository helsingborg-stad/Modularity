<?php 


if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group(array(
    'key' => 'group_641c51b765f4b',
    'title' => __('Email Subscribe', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_641c51b7d5dff',
            'label' => __('Lead', 'modularity'),
            'name' => 'content',
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
            'acfe_textarea_code' => 0,
            'maxlength' => '',
            'rows' => '',
            'placeholder' => '',
            'new_lines' => '',
        ),
        1 => array(
            'key' => 'field_641c53ff5ed70',
            'label' => __('Consent message', 'modularity'),
            'name' => 'consent_message',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => __('I want to receive relevant information from this organization to my inbox. The information provided here will not be shared or sold. I can unsubscribe at any time.', 'modularity'),
            'acfe_textarea_code' => 0,
            'maxlength' => 400,
            'rows' => 3,
            'placeholder' => '',
            'new_lines' => '',
        ),
        2 => array(
            'key' => 'field_641c5206d0a1e',
            'label' => __('Service', 'modularity'),
            'name' => 'service',
            'type' => 'select',
            'instructions' => __('Select the email provider that you want to use for this subscription form. If your service of choise isen\'t selectable. It needs to be added by a developer.', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'ungdp' => __('Ungapped (ungapped.se)', 'modularity'),
            ),
            'default_value' => false,
            'return_format' => 'value',
            'multiple' => 0,
            'placeholder' => '',
            'allow_null' => 1,
            'ui' => 0,
            'ajax' => 0,
        ),
        3 => array(
            'key' => 'field_641c527d5b8e0',
            'label' => __('Settings for Ungapped service', 'modularity'),
            'name' => 'settings_for_ungapped_service',
            'type' => 'group',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_641c5206d0a1e',
                        'operator' => '==',
                        'value' => 'ungdp',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layout' => 'block',
            'acfe_seamless_style' => 0,
            'acfe_group_modal' => 0,
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_641c52955b8e1',
                    'label' => __('Form ID', 'modularity'),
                    'name' => 'form_id',
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
                ),
                1 => array(
                    'key' => 'field_641c52955b8e2',
                    'label' => __('List IDs', 'modularity'),
                    'name' => 'list_ids',
                    'type' => 'text',
                    'instructions' => __('Comma separated list of list IDs. One for each list you want to subscribe to.', 'modularity'),
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
                ),
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-subscribe',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/subscribe',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'left',
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