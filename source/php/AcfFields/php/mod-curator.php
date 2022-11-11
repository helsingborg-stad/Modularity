<?php


if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_609b788ad04bb',
    'title' => __('Curator Social Media', 'modularity'),
    'fields' => array(
        0 => array(
            'key' => 'field_609b7894869ce',
            'label' => __('Embed Code', 'modularity'),
            'name' => 'embed_code',
            'type' => 'textarea',
            'instructions' => __('Add your curator embed code here. Thios should be the full javascript. Appeance settings in the javascript configurator will not apply.', 'modularity'),
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
            'new_lines' => '',
        ),
        1 => array(
            'key' => 'field_609b7d2ba3004',
            'label' => __('Number of posts', 'modularity'),
            'name' => 'number_of_posts',
            'type' => 'number',
            'instructions' => __('Set the number items to show.', 'modularity'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 12,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => 4,
            'max' => 24,
            'step' => '',
        ),
        2 => array(
            'key' => 'field_609baae15568c',
            'label' => __('Usage of the curator social media integration', 'modularity'),
            'name' => '',
            'type' => 'message',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => __('This is an integration module for https://curator.io. The module requires an account at the service. To integrate a feed, please head over to curator.io and follow the steps below. We assume that you already have an account, with a connected feed. 

1. Login to you account. 
2. Click on "style" in the sidebar navigation. 
3. Click on "publish feed" (style won\'t matter). 
4. Paste the embed code in the field above. 
5. Save. Your feed should no be displayed.', 'modularity'),
            'new_lines' => 'wpautop',
            'esc_html' => 0,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-curator',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/curator',
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
