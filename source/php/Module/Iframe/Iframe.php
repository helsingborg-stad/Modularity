<?php

namespace Modularity\Module\Iframe;

class Iframe extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'iframe',
            __("Iframe", 'modularity'),
            __("Iframe", 'modularity'),
            __("Outputs an embedded page.", 'modularity'),
            array(),
            null,
            null,
            3600*24*7,
            true
        );

        add_filter('acf/load_field/name=iframe_url',array($this,'sslNotice'));
    }

    public function sslNotice($field) {
        if (is_ssl() || $this->isUsingSSLProxy()) {
            $field['instructions'] = '<span style="color: #f00;">'.__("Your iframe link must start with http<strong>s</strong>://. Links without this prefix will not display.", 'modularity').'</span>';
        }
        return $field;
    }

    private function isUsingSSLProxy() {
        if ((defined('SSL_PROXY') && SSL_PROXY === true)) {
            return true;
        }
        return false;
    }
}
