<?php

namespace Modularity;

use WP;
use WP_CLI;

/**
 * Class App
 *
 * @package Modularity
 */
class Upgrade
{
    private $dbVersion = 5;
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
        WP_CLI::warning($message);
    }

    /**
     * Run upgrade functions
     *
     * @return void
     */
    public function initUpgrade()
    {
        if (empty(get_option($this->dbVersionKey))) {
            update_option($this->dbVersionKey, 0);
        }
        
        $currentDbVersion = is_numeric(get_option($this->dbVersionKey)) ? (int) get_option($this->dbVersionKey) : 0;
        if ($this->dbVersion != $currentDbVersion) {
            if (!is_numeric($this->dbVersion)) {
                wp_die(__('To be installed database version must be a number.', 'municipio'));
                return;
            }

            if (!is_numeric($currentDbVersion)) {
                $this->logError(__('Current database version must be a number.', 'municipio'));
                return; 
            }

            if ($currentDbVersion > $this->dbVersion) {
                $this->logError(
                    __(
                        'Database cannot be lower than currently installed (cannot downgrade).',
                        'municipio'
                    )
                );
                return; 
            }
            
            //Fetch global wpdb object, save to $db
            $this->globalToLocal('wpdb', 'db');

            $previousDbVersion  = $currentDbVersion;
            $currentDbVersion   = $currentDbVersion + 1;

            for ($currentDbVersion; $currentDbVersion <= $this->dbVersion; $currentDbVersion++) {
                $class = 'Modularity\Upgrade\Version\V' . $currentDbVersion;

                if (class_exists($class) && $this->db) {

                    WP_CLI::line(
                        sprintf(
                            __('Initializing database from version %s to %s.', 'municipio'),
                            $previousDbVersion,
                            $currentDbVersion
                        )
                    );

                    for($halt = 3; $halt > 0; $halt--) {
                        WP_CLI::line(
                            sprintf(
                                __('Upgrade will start in %s seconds.', 'municipio'),
                                $halt
                            )
                        );
                        WP_CLI::halt(1000);
                    }
                    
                    $version = new $class($this->db);
                    $version->upgrade();

                    WP_CLI::line(
                        sprintf(
                            __('Locking database to version %s.', 'municipio'),
                            $currentDbVersion
                        )
                    );

                    update_option($this->dbVersionKey, $currentDbVersion);

                    WP_CLI::line("Flushing cache.");
                    wp_cache_flush();
                }
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
