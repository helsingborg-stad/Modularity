<?php

namespace Modularity;

class App
{
    public function __construct()
    {
        add_action('init', array($this, 'includeAcf'), 11);
        add_action('admin_enqueue_scripts', array($this, 'enqueu'));

        new Options\General();
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
            require_once MODULARITY_PATH . 'source/acf/acf.php';

            add_action('admin_notices', function () {
                echo '<div class="notice error"><p>' .
                        __('To get the full expirience of the <strong>Modularity</strong> plugin, please activate the <a href="http://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Fields Pro</a> plugin.', 'modular') .
                     '</p></div>';
            });
        }
    }

    public function enqueu()
    {
        // Style
        wp_register_style('modularity', MODULARITY_URL . '/dist/css/modularity.min.css', false, '1.0.0');
        wp_enqueue_style('modularity');

        // Scripts
        wp_register_script('modularity', MODULARITY_URL . '/dist/js/modularity.min.js', false, '1.0.0', true);
        wp_enqueue_script('modularity');
    }
}
