<?php

namespace Modularity\Helper;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Acf
{
    public function __construct()
    {
        add_action('init', array($this, 'includeAcf'), 11);
        add_filter('acf/settings/load_json', array($this, 'jsonLoadPath'));
        //add_action('admin_init', array($this, 'importAcf'));
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

    /**
     * Search for ACF json exports with naming convention "acf-{name}.json"
     * Create field groups for matches
     * @return array Found/added field groups
     */
    public function importAcf()
    {
        $directory = new RecursiveDirectoryIterator(MODULARITY_PATH . 'source/acf-json/');
        $iterator = new RecursiveIteratorIterator($directory);
        $matches = new RegexIterator($iterator, '/acf-.+\.json$/i', RegexIterator::ALL_MATCHES, RegexIterator::USE_KEY);

        $fieldgroups = array();

        $acfImport = new \acf_settings_tools();

        foreach ($matches as $path => $match) {
            $_FILES['acf_import_file'] = array(
                'name' => $match[0][0],
                'type' => 'application/json',
                'size' => filesize($path),
                'tmp_name' => $path
            );

            $acfImport->import();
        }

        unset($acfImport);

        return $fieldgroups;
    }
}
