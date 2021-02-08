<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_569e401dd4422',
    'title' => 'Main news',
    'fields' => array(
        0 => array(
            'sub_fields' => array(
                0 => array(
                    'post_type' => array(
                    ),
                    'taxonomy' => array(
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'return_format' => 'object',
                    'ui' => 1,
                    'key' => 'field_569e4052cae00',
                    'label' => __('News item', 'modularity'),
                    'name' => 'news_item',
                    'type' => 'post_object',
                    'instructions' => __('Välj sida att länka till', 'modularity'),
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                ),
            ),
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => __('Add Row', 'modularity'),
            'collapsed' => '',
            'key' => 'field_569e4038cadff',
            'label' => __('News items', 'modularity'),
            'name' => 'main_news',
            'type' => 'repeater',
            'instructions' => __('Välj sida att länka till', 'modularity'),
            'required' => 0,
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
                'value' => 'mod-mainnews',
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
    'modified' => 1458547031,
    'local' => 'json',
));
}