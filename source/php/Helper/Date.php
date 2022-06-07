<?php

namespace Modularity\Helper;

class Date
{
    /**
     * Returns the revved/cache-busted file name of an asset.
     * @param string $name Asset name (array key) from rev-mainfest.json
     * @param boolean $returnName Returns $name if set to true while in dev mode
     * @return string filename of the asset (including directory above)
     */
    public static function getDate($format)
    {
        $defaultTime = 'H:i';
        $defaultDate = 'Y-m-d';
        if ($format === 'date') {
            return get_option('date_format') ?: get_option($defaultDate);
        } elseif ($format === 'date-time') {
            return get_option('date_format') . ' ' . get_option('time_format') ?: get_option($defaultDate) . ' ' . wp_date($defaultTime);
        } elseif ($format === 'time') {
            return get_option('time_format') ?: get_option($defaultTime);
        }
    }
}