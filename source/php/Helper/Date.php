<?php

namespace Modularity\Helper;

/**
 * Returns format for date and time
 * @param  string $format      A string that is either date or time or date-time
 * @return string              The format for date/time
 */

class Date
{
    public static function getDateFormat($format)
    {
        $defaultTime = 'H:i';
        $defaultDate = 'Y-m-d';
        $dateFormat = function_exists('get_option') && !empty(get_option('date_format')) ?
        get_option('date_format') : $defaultDate;

        $timeFormat = function_exists('get_option') && !empty(get_option('time_format')) ?
        get_option('time_format') : $defaultTime;

        if ($format === 'date') {
            return $dateFormat;
        } elseif ($format === 'time') {
            return $timeFormat;
        } elseif ($format === 'date-time') {
            return $dateFormat . ' ' . $timeFormat;
        }
    }
}