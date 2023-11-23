<?php

namespace Modularity\Module\Posts\Helper;

class PostContentFiltered
{
    public static function getPostContentFiltered($content) {
        if (!empty($content) && strpos($content, '<!--more-->') !== false) {
            $morePosition = strpos($content, '<!--more-->');
            preg_match_all('/<p.*?>/', $content, $matches, PREG_OFFSET_CAPTURE);
            
            if (!empty($matches[0])) {
                foreach ($matches[0] as $match) {
                    if ($match[1] < $morePosition) {
                        $content = substr_replace($content, '<p class="lead">', $match[1], strlen('<p>'));
                    }
                }
            }
        }

        $cleanedContent = preg_replace('/<!--(.*?)-->/', '', $content);

        return $cleanedContent;
    }
}
