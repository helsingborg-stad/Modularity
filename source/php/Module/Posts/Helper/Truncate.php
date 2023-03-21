<?php

namespace Modularity\Module\Posts\Helper;

/**
 * Class Truncate
 * @package Modularity\Module\Posts\Helper
 */
class Truncate
{
    /**
     * @param $content
     * @param $amount
     * @return array|null
     */
    public function truncate($content, $amount)
    {
        if (empty($content)) {
            return "";
        }

        $words = explode(' ', rtrim($content));
        $truncate = array_slice($words, 0, $amount);
        array_pop($truncate);

        return implode(' ', $truncate) . '...';
    }
}

