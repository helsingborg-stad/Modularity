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
        self::$available = $this->getAvailable(false);
        $this->initModules();
    }

    /**
     * Get available modules (WP filter)
     * @return array
     */
    public function getAvailable($getBundled = true)
    {
        if ($getBundled) {
            $bundeled = $this->getBundeled();
            self::$available = array_merge(self::$available, $bundeled);
        }

        return apply_filters('Modularity/Modules', self::$available);
    }

    /**
     * Gets bundeled modules
     * @return array
     */
    public function getBundeled() : array
    {
        $directory = MODULARITY_PATH . 'source/php/Module/';
        $bundeled = array();

        foreach (@glob($directory . "*", GLOB_ONLYDIR) as $folder) {
            $bundeled[$folder] = basename($folder);
        }

        return $bundeled;
    }

    /**
     * Initializes modules
     * @return void
     */
    public function initModules()
    {
        foreach (self::$available as $path => $module) {
            var_dump($path, $module);
            exit;
        }
    }
}
