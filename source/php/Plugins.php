<?php

namespace Modularity;

class Plugins
{
    public $plugins = array(
        "acf-field/focus-point/acf-focuspoint.php",
        "acf-field/table/advanced-custom-fields-table-field/trunk/acf-table.php",
        "acf-field/post-type-select/acf-posttype-select.php",
        "acf-field/website/acf-website_field.php"
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
