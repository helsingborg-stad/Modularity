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
        $fields = $this->getFields();
        $data = array();

        //Shared template data
        $data['height'] = !empty($fields['height']) ? $fields['height'] . 'px' : '400px';

        $this->template = $fields['map_type'];
        if ($fields['map_type'] == 'openStreetMap') {
            return $this->openStreetMapTemplateData($data, $fields);
        }

        return $this->defaultTemplateData($data, $fields);   
    }

    private function openStreetMapTemplateData($data, $fields) {

        $data['pins'] = array();
        $start = $fields['osm_start_position'];

if(!empty($fields['osm_markers']) && is_array($fields['osm_markers'])) {
        foreach ($fields['osm_markers'] as $marker) {
            if ($this->hasCorrectPlaceData($marker['position'])) {
                $pin = array();
                $pin['lat'] = $marker['position']['lat'];
                $pin['lng'] = $marker['position']['lng'];
                $pin['tooltip'] = $this->createMarkerTooltip($marker);

                array_push($data['pins'], $pin);
            }
                    }
        }

        if (!empty($start)) {
            $data['startPosition'] = [
                'lat' => $start['lat'], 
                'lng' => $start['lng'], 
                'zoom' => $start['zoom']
            ];
        }

        return $data;
    }

    private function defaultTemplateData($data, $fields) {
        //Get and sanitize url
        $map_url = $fields['map_url'];
        $map_url = str_replace(['http://', 'https://'], '//', $map_url); //Enforce ssl
        $map_url = str_replace('disable_scroll=false', 'disable_scroll=true', $map_url); //Remove scroll arcgis

        //Create data array
        $data['map_url']            = $map_url;
        $data['map_description']    = !empty($fields['map_description']) ? $fields['map_description'] : '';
        
        $data['show_button']        = !empty($fields['show_button']) ? $fields['show_button'] : false;
        $data['button_label']       = !empty($fields['button_label']) ? $fields['button_label'] : false;
        $data['button_url']         = !empty($fields['button_url']) ? $fields['button_url'] : false;
        $data['more_info_button']   = !empty($fields['more_info_button']) ? $fields['more_info_button'] : false;
        $data['more_info']          = !empty($fields['more_info']) ? $fields['more_info'] : false;
        $data['more_info_title']    = !empty($fields['more_info_title']) ? $fields['more_info_title'] : false;

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

    private function hasCorrectPlaceData($position): bool {
        return !empty($position) && !empty($position['lat'] && !empty($position['lng']));
    }

    private function createMarkerTooltip($marker) {
        $tooltip = array();
        $tooltip['title'] = $marker['title'];
        $tooltip['excerpt'] = $marker['description'];
        $tooltip['directions']['label'] = $marker['link_text'];
        $tooltip['directions']['url'] = $marker['url'];

        return $tooltip;
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
        $value = htmlspecialchars_decode($value);
        return $value;
    }

    public function template() {
        $path = __DIR__ . "/views/" . $this->template . ".blade.php";

        if (file_exists($path)) {
            return $this->template . ".blade.php";
        }
        
        return 'default.blade.php';
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
