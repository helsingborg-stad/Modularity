<?php

namespace Modularity;

class ModuleManager
{
    public static $available = array();
    public static $widths = array();
    public static $deprecated = array();
    public static $enabled = array();

    public function __construct()
    {
        self::$enabled = self::getEnabled();

        $this->initBundledModules();

        // Hide title option
        add_action('edit_form_before_permalink', array($this, 'hideTitleCheckbox'));
        add_action('save_post', array($this, 'saveHideTitleCheckbox'), 10, 2);
    }

    /**
     * Initializes bundled modules which is set to be active in the Modularity options
     * @return void
     */
    private function initBundledModules()
    {
        $directory = MODULARITY_PATH . 'source/php/Module/';

        foreach (@glob($directory . "*", GLOB_ONLYDIR) as $folder) {
            $class = '\Modularity\Module\\' . basename($folder) . '\\' . basename($folder);

            if (class_exists($class)) {
                new $class;
            } elseif (function_exists('error_log')) {
                error_log(__("Error: Could not init '" . $class . "' module.", 'modularity'));
            }
        }
    }

    /**
     * Get enabled modules id:s
     * @return array
     */
    public static function getEnabled()
    {
        $options = get_option('modularity-options');

        if (!isset($options['enabled-modules'])) {
            return array();
        }

        return $options['enabled-modules'];
    }
}
