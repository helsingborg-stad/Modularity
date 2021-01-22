<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_56a8b9eddfced',
    'title' => __('Embed', 'modularity'),
    'fields' => array(
        0 => array(
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
        1 => array(
            'key' => 'field_60097ea2942bb',
            'label' => __('Card Padding', 'modularity'),
            'name' => 'embeded_card_padding',
            'type' => 'range',
            'instructions' => __('Add padding to card (default: 0, no padding)', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
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