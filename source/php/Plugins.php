<?php

namespace Modularity;

class Plugins
{
    public $plugins = array(
        'acf-post-type-field/acf-posttype-select.php',
        'acf-dynamic-table-field/acf-anagram_dynamic_table_field.php',
    );

    public function __construct()
    {
        $this->plugins = apply_filters('Modularity/Plugins', $this->plugins);

        foreach ($this->plugins as $plugin) {
            if (file_exists($plugin)) {
                require_once $plugin;
            } elseif (file_exists(MODULARITY_PATH . 'plugins/'. $plugin)) {
                require_once MODULARITY_PATH . 'plugins/'. $plugin;
            }
        }
    }
}
