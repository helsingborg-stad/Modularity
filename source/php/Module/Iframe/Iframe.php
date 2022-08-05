<?php

namespace Modularity\Module\Iframe;

class Iframe extends \Modularity\Module
{
    public $slug = 'iframe';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __('Iframe', 'modularity');
        $this->namePlural = __('Iframe', 'modularity');
        $this->description = __("Outputs an embedded page.", 'modularity');

        add_filter('acf/load_field/name=iframe_url', array($this,'sslNotice'));

    }

    public function script() {
          wp_register_script('iframe-acceptance', MODULARITY_URL . '/dist/'
            . \Modularity\Helper\CacheBust::name('js/iframe-acceptance.js'));
        wp_enqueue_script('iframe-acceptance');
    }



    public function data() : array
    {
        $url = get_field('iframe_url', $this->ID);
        $url = str_replace(array('http://', 'https://'), '//', $url);
 
        $data['url'] = $url;
        $data['height'] = get_field('iframe_height', $this->ID);
        $data['description'] = get_field('iframe_description', $this->ID);

        return $data;
    }

    public function sslNotice($field)
    {
        if (is_ssl() || $this->isUsingSSLProxy()) {
            $field['instructions'] = '<span style="color: #f00;">'.__("Your iframe link must start with http<strong>s</strong>://. Links without this prefix will not display.", 'modularity').'</span>';
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
