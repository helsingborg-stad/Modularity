<?php

namespace Modularity\Helper;

class React
{
    public static function enqueue($version = false)
    {
        // Use minified libraries if SCRIPT_DEBUG is turned off
        $suffix = (defined('DEV_MODE') && DEV_MODE) ? 'development' : 'production.min';

        $version = (is_string($version) && !empty($version)) ? $version : '16.4.2';

        //@babel/polyfill
        if (!wp_script_is('@babel/polyfill')) {
            wp_enqueue_script('@babel/polyfill', 'https://cdn.jsdelivr.net/npm/@babel/polyfill@7.0.0/dist/polyfill' . (defined('DEV_MODE') && DEV_MODE) ? '.min.js' : '.js', array(), null);
        }

        //Enqueue react
        if (!wp_script_is('react')) {
            wp_enqueue_script('react', 'https://unpkg.com/react@16/umd/react.' . $suffix . '.js', array(), null);
        }

        if (!wp_script_is('react-dom')) {
            wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@16/umd/react-dom.' . $suffix . '.js', array('react'), null);
        }
    }
}
