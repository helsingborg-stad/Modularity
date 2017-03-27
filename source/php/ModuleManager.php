<?php

namespace Modularity;

class ModuleManager
{
    /**
     * Holds a list of available (initialized) modules
     * @var array
     */
    public static $available = array();

    /**
     * Holds a list of enabled modules
     * @var array
     */
    public static $enabled = array();

    /**
     * Holds a list of deprecated modules
     * @var array
     */
    public static $deprecated = array();

    /**
     * Available width settings for modules
     * @var array
     */
    public static $widths = array();

    public function __construct()
    {
        $this->getAvailable();
        $this->initModules();
    }

    /**
     * Get available modules (WP filter)
     * @return array
     */
    public function getAvailable()
    {
        return apply_filters('Modularity/Modules', self::$available);
    }

    public function initModules()
    {
        foreach (self::$available as $path => $module) {

        }
    }
}
