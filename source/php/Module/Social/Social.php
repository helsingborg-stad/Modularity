<?php

namespace Modularity\Module\Social;

class Social extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'social',
            __("Social Media Feed", 'modularity-plugin'),
            __("Sociala Media Feeds", 'modularity-plugin'),
            __("Outputs a social media feed from desired username or hashtag (facebook, instagram, twitter, linkedin).", 'modularity-plugin'),
            array()
        );

        add_action('plugins_loaded', array($this,'acfFields'));
    }

    public function acfFields()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_5666a4ec3c437',
                'title' => 'Social',
                'fields' => array(
                    array(
                        'key' => 'field_5666a4eed3288',
                        'label' => 'Show title',
                        'name' => 'mod_social_show_title',
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
                            'true' => 'Yes',
                            'false' => 'No',
                        ),
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'true',
                        'layout' => 'horizontal',
                    ),
                    array(
                        'key' => 'field_5666a56bd3289',
                        'label' => 'Feed type',
                        'name' => 'mod_social_feed_type',
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
                            'facebook' => 'Facebook',
                            'instagram' => 'Instagram',
                            'twitter' => 'Twitter',
                        ),
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'facebook',
                        'layout' => 'horizontal',
                    ),
                    array(
                        'key' => 'field_5666a65b38131',
                        'label' => 'Instagram Client ID',
                        'name' => 'mod_social_instagram_client_id',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'instagram',
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
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_5666a6a938132',
                        'label' => 'Facebook App ID',
                        'name' => 'mod_social_facebook_app_id',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'facebook',
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
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_5666a6ce38133',
                        'label' => 'Facebook App Secret',
                        'name' => 'mod_social_facebook_app_secret',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'facebook',
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
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_5666a76a7bb40',
                        'label' => 'Faceboook username',
                        'name' => 'mod_social_faceboook_username',
                        'type' => 'text',
                        'instructions' => 'The username of the Facebook user or page to fetch feed from. Attention: Only public user\'s feed can be fetched.',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'facebook',
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
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_5666a6d938134',
                        'label' => 'Twitter Consumer Key',
                        'name' => 'mod_social_twitter_consumer_key',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'twitter',
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
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_5666a6ec38135',
                        'label' => 'Twitter Consumer Secret',
                        'name' => 'mod_social_twitter_consumer_secret',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'twitter',
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
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_5666a7ca7bb41',
                        'label' => 'Hashtag',
                        'name' => 'mod_social_hashtag',
                        'type' => 'text',
                        'instructions' => 'The hashtag of the feed to fetch. Only one is allowed.',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'instagram',
                                ),
                            ),
                            array(
                                array(
                                    'field' => 'field_5666a56bd3289',
                                    'operator' => '==',
                                    'value' => 'twitter',
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
                        'prepend' => '#',
                        'append' => '',
                        'maxlength' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'mod-social',
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
            ));
        }
    }
}
