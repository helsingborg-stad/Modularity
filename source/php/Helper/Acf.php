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
        add_filter('acf/settings/l10n', function () {
            return true;
        });
    }

    /**
     * Includes Advanced Custom Fields if missing, notifies user to activate ACF PRO to get full expirience
     * @return void
     */
    public function includeAcf()
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        if (!class_exists('acf')) {
            require_once MODULARITY_PATH . 'plugins/acf/acf.php';

            add_action('admin_notices', function () {
                echo '<div class="notice error"><p>' .
                        __('To get the full expirience of the <strong>Modularity</strong> plugin, please activate the <a href="http://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Fields Pro</a> plugin.', 'modularity') .
                     '</p></div>';
            });
        }
    }
}
