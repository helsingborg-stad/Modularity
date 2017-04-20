<?php

namespace Modularity;

class Activation
{
    public function __construct()
    {
        add_action("activated_plugin", array($this, 'reorderActivePlugins'));
        add_action("deactivated_plugin", array($this, 'reorderActivePlugins'));
    }

    /**
     * Function to manipulate the load order of plugins.
     * @param  string   $plugin - Not used
     * @param  boolean  $network_activation - Tell if we are in network admin or not.
     * @return boolean
     * @since  Version 2.0.2
     * @depricated May be removed shortly
     */
    public function reorderActivePlugins($plugin, $network_activation)
    {

        //Get currect plugin order
        if ($network_activation) {
            $plugin = get_site_option('active_sitewide_plugins');
        } else {
            $plugin = get_option('active_plugins');
        }

        //Move Modularity to the top
        if (is_array($plugin) && !empty($plugin)) {
            foreach ($plugin as $plugin_key => $plugin_path) {
                if (str_replace(WP_PLUGIN_DIR, "", MODULARITY_PATH).'modularity.php' == $plugin_path) {
                    $temp = array($plugin_key => $plugin[$plugin_key]);
                    unset($plugin[$plugin_key]);
                    $plugin = $temp + $plugin;
                }
            }
        }

        //Update plugin order
        if ($network_activation) {
            update_site_option('active_sitewide_plugins', $plugin);
        } else {
            update_option('active_plugins', $plugin);
        }
    }
}
