<?php 


if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group(array(
    'key' => 'group_575a842dd1283',
    'title' => __('Notice settings', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_575a8454ea3b4',
            'label' => __('Type', 'modularity'),
            'name' => 'notice_type',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'success' => __('Success (green)', 'modularity'),
                'info' => __('Info (black)', 'modularity'),
                'warning' => __('Warning (yellow)', 'modularity'),
                'danger' => __('Danger (red)', 'modularity'),
            ),
            'default_value' => __('success', 'modularity'),
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
        1 => array(
            'key' => 'field_575a8436ea3b3',
            'label' => __('Notice', 'modularity'),
            'name' => 'notice_text',
            'aria-label' => '',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 1,
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
            'new_lines' => 'br',
        ),
        2 => array(
            'key' => 'field_67a3376a096cd',
            'label' => __('Include link', 'modularity'),
            'name' => 'include_link',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => __('Yes', 'modularity'),
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        3 => array(
            'key' => 'field_67a33791096ce',
            'label' => __('Link', 'modularity'),
            'name' => 'link',
            'aria-label' => '',
            'type' => 'link',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_67a3376a096cd',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
        ),
        4 => array(
            'key' => 'field_67a337b2096cf',
            'label' => __('Link position', 'modularity'),
            'name' => 'link_position',
            'aria-label' => '',
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_67a3376a096cd',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'aside' => __('Aside text', 'modularity'),
                'below' => __('Below text', 'modularity'),
            ),
            'default_value' => __('below', 'modularity'),
            'return_format' => 'value',
            'allow_null' => 0,
            'other_choice' => 0,
            'layout' => 'horizontal',
            'save_other_choice' => 0,
        ),
        5 => array(
            'key' => 'field_67ab264cf9291',
            'label' => __('Dismissible', 'modularity'),
            'name' => 'dismissible',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => __('Yes', 'modularity'),
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        6 => array(
            'key' => 'field_67ab2686f9293',
            'label' => __('Dismissal time', 'modularity'),
            'name' => 'dismissal_time',
            'aria-label' => '',
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_67ab264cf9291',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'session' => __('Dismissed until user restarts browser', 'modularity'),
                'permanent' => __('Dismissed "forever"', 'modularity'),
                'immediate' => __('Dismissed until page reload', 'modularity'),
            ),
            'default_value' => __('session', 'modularity'),
            'return_format' => 'value',
            'allow_null' => 0,
            'other_choice' => 0,
            'layout' => 'horizontal',
            'save_other_choice' => 0,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-notice',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/notice',
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