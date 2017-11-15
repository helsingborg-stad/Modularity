<?php

namespace Modularity\Module\Social;

class Social extends \Modularity\Module
{
    public $slug = 'social';
    public $supports = array();

    public $feedArgs;

    public function init()
    {
        $this->nameSingular = __("Social Media Feed", 'modularity');
        $this->namePlural = __("Sociala Media Feeds", 'modularity');
        $this->description = __("Outputs a social media feed from desired username or hashtag (facebook, instagram, twitter, linkedin).", 'modularity');
    }

    public function data() : array
    {
        $data['feed'] = $this->getFeed();
        $data['feedArgs'] = $this->feedArgs;
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->post_type, $this->args));
        return $data;
    }

    public function getFeed()
    {

        $fields = json_decode(json_encode(get_fields($this->ID)));

        $feedArgs = array(
            'network'    => isset($fields->mod_social_type) ? $fields->mod_social_type : '',
            'type'       => isset($fields->mod_social_data_type) ? $fields->mod_social_data_type : '',
            'query'      => isset($fields->mod_social_query) ? $fields->mod_social_query : '',
            'length'     => isset($fields->mod_social_length) ? $fields->mod_social_length : 10,
            'max_height' => isset($fields->mod_social_max_height) ? $fields->mod_social_max_height : 300,
            'row_length' => isset($fields->mod_social_row_length) ? $fields->mod_social_row_length : 3,
            'api_user'   => isset($fields->mod_social_api_user) ? $fields->mod_social_api_user : '',
            'api_secret' => isset($fields->mod_social_api_secret) ? $fields->mod_social_api_secret : '',
            'page_link'  => isset($fields->mod_social_link) ? $fields->mod_social_link : false,
            'link_url'   => isset($fields->mod_social_link_url) ? $fields->mod_social_link_url : '',
            'link_text'  => isset($fields->mod_social_link_text) ? $fields->mod_social_link_text : ''
        );

        $this->feedArgs = $feedArgs;

        return new \Modularity\Module\Social\Feed($feedArgs);
    }

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
