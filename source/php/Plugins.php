<?php

namespace Modularity;

class Plugins
{
    public $plugins = array(
        "clark-nikdel-powell/post-type-select-for-acf/acf-posttype-select.php",
        "jeradin/acf-website-field/acf-website_field.php",
        "ooksanen/acf-focuspoint/acf-focuspoint.php",
        "jeradin/acf-dynamic-table-field/acf-anagram_dynamic_table_field.php",
        "wpackagist-plugin/acf-extended/acf-extended.php"
    );

    public function __construct()
    {
        $this->plugins = apply_filters('Modularity/Plugins', $this->plugins);

        if(is_array($this->plugins) && !empty($this->plugins)) {
            foreach ($this->plugins as $plugin) {

                //Paths to try
                $pluginPath = [
                    'common'    => ABSPATH . "../vendor/" . $plugin,
                    'local'     => MODULARITY_PATH . "vendor/" . $plugin
                ];

                //Include either one
                if(file_exists($pluginPath['local'])) {
                    require_once $pluginPath['local'];
                }
                elseif (file_exists($pluginPath['common'])) {
                    require_once $pluginPath['common'];
                } 
            }
        }
    }
}
