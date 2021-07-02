<?php

namespace Modularity;

class Plugins
{
    public $plugins = array(
        "johannheyne/advanced-custom-fields-table-field/advanced-custom-fields-table-field/trunk/acf-table.php",
        "clark-nikdel-powell/post-type-select-for-acf/acf-posttype-select.php",
        "jeradin/acf-website-field/acf-website_field.php",
        "ooksanen/acf-focuspoint/acf-focuspoint.php"
    );

    public function __construct()
    {
        $this->plugins = apply_filters('Modularity/Plugins', $this->plugins);

        foreach ($this->plugins as $plugin) {

            $pluginPath = MODULARITY_PATH . "vendor/" . $plugin; 

            if (file_exists($pluginPath)) {
                require_once $pluginPath;
            } else {
                error_log("A plugin not existing tried to enqueue to acf in modularity."); 
            }
        }
    }
}
