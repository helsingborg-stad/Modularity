<?php

namespace Modularity\Module\Slider;

class Slider extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'slider',
            __("Slider", 'modularity'),
            __("Sliders", 'modularity'),
            __("Outputs multiple images or videos in a sliding apperance.", 'modularity'),
            array(),
            null,
            null,
            3600*24*7
        );
    }

    public static function getEmbed($url, $classes = array(), $image = null)
    {
        $src = null;
        $classes = count($classes) > 0 ? 'class="' . implode(' ', $classes) . '"' : '';

        if (strpos($url, 'youtu') > -1) {
            $id = parse_str(parse_url($url, PHP_URL_QUERY), $urlParts);

            if (!isset($urlParts['v'])) {
                return null;
            }

            $src = '<div ' . $classes  . '  style="background-image:url(\'' . (($image !== false) ? $image[0] : '') . '\');"><a data-unavailable="' . __('Video playback unavailable, please activate JavaScript to enable.', 'modularity') .'" href="#video-player-' . $urlParts['v']  . '" data-video-id="' . $urlParts['v'] . '"></a></div>';
        } elseif (strpos($url, 'vimeo') > -1) {
            $id = preg_match_all('/.*\/([0-9]+)$/i', $url, $matches);

            if (!isset($matches[1][0])) {
                return null;
            }

            $src = '<div class="player ratio-16-9 ' . $classes  . '"  style="background-image:url(\'' . (($image !== false) ? $image[0] : '') . '\');"><a data-unavailable="' . __('Video playback unavailable, please activate JavaScript to enable.', 'modularity') .'" href="#video-player-' . $matches[1][0]  . '" data-video-id="' . $matches[1][0] . '"></a></div>';
        }

        return $src;
    }
}
