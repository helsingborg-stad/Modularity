<?php

namespace Modularity;

class Activation
{

    public function __construct()
    {
        //Runtime hooks
        add_filter('option_active_plugins', array($this, 'doReorder'));
        add_filter('site_option_active_sitewide_plugins', array($this, 'doReorder'));
    }

    /**
     * Function to manipulate the load order of plugins.
     * @param  string   $plugin - Not used
     * @param  boolean  $network_activation - Tell if we are in network admin or not.
     * @return void
     * @since  Version 2.0.2
     * @depricated May be removed shortly
     */
    public function reorderActivePlugins($plugin, $network_activation = false)
    {
        //Get currect plugin order
        if ($network_activation && is_multisite()) {
            $plugin = get_site_option('active_sitewide_plugins');
        } else {
            $plugin = get_option('active_plugins');
        }

        //Reorder
        $plugin = $this->doReorder($plugin);

        //Update plugin order
        if (is_array($plugin) && !empty($plugin)) {
            if ($network_activation && is_multisite()) {
                update_site_option('active_sitewide_plugins', $plugin);
            } else {
                update_option('active_plugins', $plugin);
            }
        }
    }

    /**
     * Function to manipulate the load order of plugins.
     * @param  string   $plugin - Not used
     * @param  sting    $option_name - The name of any option hooking with this class
     * @return array
     * @since  Version 2.0.3
     * @depricated May be removed shortly
     */
    public function doReorder($plugin, $option_name = null)
    {
        if (!is_array($plugin)) {
            return array();
        }

        if (!$this->isAssocArray($plugin)) {
            if (array_unshift($plugin, str_replace(WP_PLUGIN_DIR."/", "", MODULARITY_PATH).'modularity.php')) {
                return array_unique($plugin);
            }
        }

        if ($this->isAssocArray($plugin)) {
            return array_unique(array(str_replace(WP_PLUGIN_DIR."/", "", MODULARITY_PATH).'modularity.php' => (time()-(365*24*60*60))) + $plugin);
        }

        return $plugin;
    }

    /**
     * Function to check if what format that is stored
     * @param  arra   $array - Array to check
     * @since  Version 2.0.3
     * @depricated May be removed shortly
     */
    private function isAssocArray($array)
    {
        foreach ($array as $key => $item) {
            if (!is_numeric($key)) {
                return true;
            }
        }
        return false;
    }
}
