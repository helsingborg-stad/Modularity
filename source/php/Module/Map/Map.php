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
        add_filter('acf/load_field/name=map_url_large', array($this,'sslNotice'));
    }

    public function data() : array
    {
        $map_url = get_field('map_url', $this->ID);
        $map_url_large = get_field('map_url_large', $this->ID);

        $map_url = str_replace(array('http://', 'https://'), '//', $map_url);
        $map_url_large = str_replace(array('http://', 'https://'), '//', $map_url_large);

        $data['map_url'] = $map_url;
        $data['map_url_large'] = $map_url_large; 

        $data['height'] = get_field('height', $this->ID);
        $data['button_label'] = __('Show larger map', 'modularity'); 

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
