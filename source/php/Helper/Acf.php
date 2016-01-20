<?php

namespace Modularity\Helper;

class Acf
{
    public function __construct()
    {
        add_action('init', array($this, 'includeAcf'), 11);
        add_filter('acf/settings/load_json', array($this, 'jsonLoadPath'));
    }

    /**
     * Includes Advanced Custom Fields if missing, notifies user to activate ACF PRO to get full expirience
     * @return void
     */
    public function includeAcf()
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        if (!is_plugin_active('advanced-custom-fields-pro/acf.php')
            && !is_plugin_active('advanced-custom-fields/acf.php')
        ) {
            require_once MODULARITY_PATH . 'plugins/acf/acf.php';

            add_action('admin_notices', function () {
                echo '<div class="notice error"><p>' .
                        __('To get the full expirience of the <strong>Modularity</strong> plugin, please activate the <a href="http://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Fields Pro</a> plugin.', 'modular') .
                     '</p></div>';
            });
        }
    }

    public function jsonLoadPath($paths)
    {
        $paths[] = MODULARITY_PATH . 'source/acf-json';
        return $paths;
    }
}
