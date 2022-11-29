<?php

namespace Modularity\Module\Map;

class Map extends \Modularity\Module
{
    public $slug = 'map';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __('Map', 'modularity');
        $this->namePlural = __('Maps', 'modularity');
        $this->description = __("Outputs an embedded map.", 'modularity');

        add_filter('acf/load_field/name=map_url', array($this,'sslNotice'));
        add_filter('acf/load_value/name=map_url', array($this,'filterMapUrl'), 10, 3);
        add_filter('acf/update_value/name=map_url', array($this,'filterMapUrl'), 10, 3);
    }

    public function data() : array
    {
        //Get and sanitize url
        $map_url = get_field('map_url', $this->ID);
        $map_url = str_replace(['http://', 'https://'], '//', $map_url); //Enforce ssl
        $map_url = str_replace('disable_scroll=false', 'disable_scroll=true', $map_url); //Remove scroll arcgis

        //Create data array
        $data['map_url']            = $map_url;

        $data['height']             = get_field('height', $this->ID);
        $data['map_description']    = get_field('map_description', $this->ID);
        
        $data['show_button']        = get_field('show_button', $this->ID);
        $data['button_label']       = get_field('button_label', $this->ID);
        $data['button_url']         = get_field('button_url', $this->ID);
        $data['more_info_button']   = get_field('more_info_button', $this->ID);
        $data['more_info']          = get_field('more_info', $this->ID);
        $data['more_info_title']    = get_field('more_info_title', $this->ID);

        $data['cardMapCss']         = ($data['more_info_button']) ? 'o-grid-12@xs o-grid-8@md' : 'o-grid-12@md';
        $data['cardMoreInfoCss']    = ($data['more_info_button']) ? 'o-grid-12@xs o-grid-4@md' : '';

        $data['uid']                = uniqid();
        $data['id']                 = $this->ID;

        $data['lang'] = (object) [
            'knownLabels' => [
                'title' => __('We need your consent to continue', 'modularity'),
                'info' => sprintf(__('This part of the website shows content from %s. By continuing, <a href="%s"> you are accepting GDPR and privacy policy</a>.', 'modularity'), '{SUPPLIER_WEBSITE}', '{SUPPLIER_POLICY}'),
                'button' => __('I understand, continue.', 'modularity'),
            ],

            'unknownLabels' => [
                'title' => __('We need your consent to continue', 'modularity'),
                'info' => sprintf(__('This part of the website shows content from another website (%s). By continuing, you are accepting GDPR and privacy policy.', 'municipio'), '{SUPPLIER_WEBSITE}'),
                'button' => __('I understand, continue.', 'modularity'),
            ],
        ];

        return $data;
    }

    public function sslNotice($field)
    {
        if (is_ssl() || $this->isUsingSSLProxy()) {
            $field['instructions'] = '<span style="color: #f00;">'.__("Your map link must start with http<strong>s</strong>://. Links without this prefix will not display.", 'modularity').'</span>';
        }

        return $field;
    }

    private function isUsingSSLProxy()
    {
        if ((defined('SSL_PROXY') && SSL_PROXY === true)) {
            return true;
        }

        return false;
    }

    public function filterMapUrl($value, $post_id, $field) 
    {
        $value = str_replace('&amp;', '&', $value);
        return $value;
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
