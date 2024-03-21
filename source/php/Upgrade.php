<?php

namespace Modularity;

/**
 * Class App
 *
 * @package Modularity
 */
class Upgrade
{
    private $dbVersion = 6; //The db version we want to achive
    private $dbVersionKey = 'modularity_db_version';
    private $db;

    /**
     * App constructor.
     */
    public function __construct()
    {
        add_action('wp', array($this, 'initUpgrade'), 10);
    }

    /**
     * Reset db version, in order to run all scripts from the beginning.
     *
     * @return void
     */
    public function reset()
    {
        update_option($this->dbVersionKey, 0);
    }

    /**
     * Logs error message
     *
     * @param string $message Error message
     *
     */
    private function logError(string $message)
    {
        error_log($message);
    }

    /**
     * Run upgrade functions
     *
     * @return void
     */
    public function initUpgrade()
    {
        // if(!is_admin()) {
        //     return;
        // }

        if (empty(get_option($this->dbVersionKey))) {
            update_option($this->dbVersionKey, 0);
        }
        
        // $currentDbVersion = is_numeric(get_option($this->dbVersionKey)) ? (int) get_option($this->dbVersionKey) : 0;
        $currentDbVersion = 5;
        if ($this->dbVersion != $currentDbVersion) {
            if (!is_numeric($this->dbVersion)) {
                wp_die(__('To be installed database version must be a number.', 'municipio'));
            }

            if (!is_numeric($currentDbVersion)) {
                $this->logError(__('Current database version must be a number.', 'municipio'));
            }

            if ($currentDbVersion > $this->dbVersion) {
                $this->logError(
                    __(
                        'Database cannot be lower than currently installed (cannot downgrade).',
                        'municipio'
                    )
                );
            }
            
            
            //Fetch global wpdb object, save to $db
            $this->globalToLocal('wpdb', 'db');
            while ($currentDbVersion <= $this->dbVersion) {
                $class = 'Modularity\Upgrade\Version\V' . $currentDbVersion;
                if (class_exists($class) && $this->db) {
                    $version = new $class($this->db);
                    $version->upgrade();
                }
                
                $currentDbVersion++;
            }
        }
    }

    /**
     * Creates a local copy of the global instance
     * The target var should be defined in class header as private or public
     * @param string $global The name of global varable that should be made local
     * @param string $local Handle the global with the name of this string locally
     * @return void
     */
    private function globalToLocal($global, $local = null)
    {
        global $$global;

        if (is_null($$global)) {
            return false;
        }

        if (is_null($local)) {
            $this->$global = $$global;
        } else {
            $this->$local = $$global;
        }

        return true;
    }
}
