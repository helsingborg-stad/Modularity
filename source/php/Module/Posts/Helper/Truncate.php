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

        $words = explode(' ', $content);
        $truncate = array_slice($words, 0, $amount);
        $readMore = '...';
        $last = strip_tags(end($truncate));

        if (end(str_split($last)) == '.') {
            $readMore = '..';
        }

        array_pop($truncate);
        $truncate[] = $last;

        return implode(' ', $truncate) . $readMore;
    }
}

