<?php

namespace Modularity\Module\Slider;

class Slider extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'slider',
            __("Slider", 'modularity-plugin'),
            __("Sliders", 'modularity-plugin'),
            __("Outputs multiple images or videos in a sliding apperance.", 'modularity-plugin'),
            array(),
            null
        );
    }

    public static function getEmbed($url, $classes = array())
    {
        $src = null;
        $classes = count($classes) > 0 ? 'class="' . implode(' ', $classes) . '"' : '';

        if (strpos($url, 'youtu') > -1) {
            $id = parse_str(parse_url($url, PHP_URL_QUERY), $urlParts);

            if (!isset($urlParts['v'])) {
                return null;
            }

            $src .= '<iframe ' . $classes . ' width="560" height="315" src="https://www.youtube.com/embed/' . $urlParts['v'] . '?hd=1&controls=0&showinfo=0&rel=0&loop=1&enablejsapi=1&autoplay=1" frameborder="0" allowfullscreen></iframe>';
        }
        elseif (strpos($url, 'vimeo') > -1) {
            $id = preg_match_all('/.*\/([0-9]+)$/i', $url, $matches);

            if (!isset($matches[1][0])) {
                return null;
            }

            $src = '<iframe ' . $classes . ' src="https://player.vimeo.com/video/' . $matches[1][0] . '?title=0&byline=0&portrait=0&autoplay=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }

        return $src;
    }
}
