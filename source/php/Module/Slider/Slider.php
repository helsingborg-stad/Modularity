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

    public static function getEmbed($url)
    {
    	$src = null;

    	if (strpos($url, 'youtu') > -1) {
    		$id = parse_str(parse_url($url, PHP_URL_QUERY), $urlParts);

    		if (!isset($urlParts['v'])) {
    			return null;
    		}

    		$src = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $urlParts['v'] . '" frameborder="0" allowfullscreen></iframe>';
    	}
    	elseif (strpos($url, 'vimeo') > -1) {
    		$id = preg_match_all('/.*\/([0-9]+)$/i', $url, $matches);

    		if (!isset($matches[1][0])) {
    			return null;
    		}

    		$src = '<iframe src="https://player.vimeo.com/video/' . $matches[1][0] . '" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    	}

    	return $src;
    }
}
