<?php

namespace Modularity;

class Plugins
{
    public $plugins = array(
        "johannheyne/advanced-custom-fields-table-field/advanced-custom-fields-table-field/trunk/acf-table.php",
        "clark-nikdel-powell/post-type-select-for-acf/acf-posttype-select.php",
        "jeradin/acf-website-field/acf-website_field.php",
        "ooksanen/acf-focuspoint/acf-focuspoint.php",
        "jeradin/acf-dynamic-table-field/acf-anagram_dynamic_table_field.php"
    );

    public function __construct()
    {
        $this->plugins = apply_filters('Modularity/Plugins', $this->plugins);

        if(is_array($this->plugins) && !empty($this->plugins)) {
            foreach ($this->plugins as $plugin) {

                $pluginPath = MODULARITY_PATH . "vendor/" . $plugin; 

                if (file_exists($pluginPath)) {
                    require_once $pluginPath;
                } else {
                    error_log("A plugin not existing tried to enqueue to acf in modularity. Have you run composer install?"); 
                }
            }
        } else {
            error_log("No plugins acf plugins found in array for modulary, please review your filters."); 
        }
    }
}
