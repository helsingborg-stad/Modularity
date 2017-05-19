<?php

namespace Modularity\Module\Slider;

class Slider extends \Modularity\Module
{
    public $slug = 'slider';
    public $supports = array();

    public $imageSizes = array(
        'ratio-16-9' => array(1250, 703),
        'ratio-10-3' => array(1250, 375),
        'ratio-36-7' => array(1800, 350),
        'ratio-4-3'  => array(1252, 939)
    );

    public $slideColumns;

    public function init()
    {
        $this->nameSingular = __("Slider", 'modularity');
        $this->namePlural = __("Sliders", 'modularity');
        $this->description = __("Outputs multiple images or videos in a sliding apperance.", 'modularity');
    }

    public function data() : array
    {
        $data = get_fields($this->ID);
        $data['classes'] = $this->getClasses($data);
        $data['flickity'] = $this->getFlickitySettings($data);

        if ($data['slider_layout'] === 'circles') {
            $data['slider_format'] = null;
        }

        $data['slides'] = $this->prepareSlides($data);
        $data['slideColumns'] = $this->slideColumns;

        return $data;
    }

    public function prepareSlides($data)
    {
        $imageSize = array(1800, 350);
        if (isset($this->imageSizes[$data['slider_format']])) {
            $imageSize = $this->imageSizes[$data['slider_format']];
        }

        foreach ($data['slides'] as &$slide) {
            $currentImageSize = $imageSize;

            if ($slide['acf_fc_layout'] === 'video') {
                $currentImageSize = array(1140,641);
            }

            if ($slide['acf_fc_layout'] == "featured") {
                $currentImageSize = array(floor($currentImageSize[0]/2), $currentImageSize[1]);
            }

            // Image
            $slide['image_use'] = false;
            if (isset($slide['image']) && !empty($slide['image'])) {
                $slide['image_use'] = wp_get_attachment_image_src(
                    $slide['image']['id'],
                    apply_filters(
                        'Modularity/slider/image',
                        $currentImageSize,
                        $this->args
                    )
                );
            }

            // Mobile image
            $slide['mobile_image_use'] = $slide['image_use'];
            if (isset($slide['mobile_image']) && !empty($slide['mobile_image'])) {
                $slide['mobile_image_use'] = wp_get_attachment_image_src(
                    $slide['mobile_image']['id'],
                    apply_filters(
                        'Modularity/slider/mobile_image',
                        array(500, 500),
                        $this->args
                    )
                );
            }

            // In some cases ACF will return an post-id instead of a link.
            if (isset($slide['link_url']) && is_numeric($slide['link_url']) && get_post_status($slide['link_url']) == "publish") {
                $slide['link_url'] = get_permalink($slide['link_url']);
            }
        }

        return $data['slides'];
    }

    public function getClasses($fields)
    {
        $classes = array();
        $classes[] = 'slider';

        if ($fields['navigation_position'] == 'bottom') {
            $classes[] = 'slider-nav-bottom';
        }

        if ($fields['show_navigation'] == "hover") {
            $classes[] = 'slider-nav-hover';
        }

        if ($fields['slider_height'] == true) {
            $classes[] = 'slider-height-restrictions';
        }

        return implode(' ', $classes);
    }

    public function getFlickitySettings($fields)
    {
        $slideColumns = isset($fields['slide_columns']) && !empty($fields['slide_columns']) ? $fields['slide_columns'] : 1;

        $flickity = array(
            'cellSelector'   => '.slide',
            'cellAlign'      => $fields['slide_align'] ? $fields['slide_align'] : 'center',
            'wrapAround'     => in_array('wrapAround', (array) $fields['additional_options']),
            'pageDots'       => in_array('pageDots', (array) $fields['additional_options']),
            'freeScroll'     => in_array('freeScroll', (array) $fields['additional_options']),
            'groupCells'     => in_array('groupCells', (array) $fields['additional_options']),
            'setGallerySize' => false
        );

        if ($fields['slides_autoslide'] === true) {
            $flickity['autoPlay'] = true;
            $flickity['pauseAutoPlayOnHover'] = true;

            if (!empty($fields['slides_slide_timeout'])) {
                $flickity['autoPlay'] = (int) $fields['slides_slide_timeout'] * 1000;
            }
        }

        if (count($fields['slides']) <= $slideColumns) {
            $flickity = array_merge($flickity, array(
                'draggable' => false,
                'pageDots' => false,
                'prevNextButtons' => false,
                'autoPlay' => false,
                'cellAlign' => 'left'
            ));

            $slideColumns = count($slides);
        }

        $this->slideColumns = $slideColumns;

        $flickity = json_encode($flickity);
        return $flickity;
    }

    public static function getEmbed($url, $classes = array(), $image = null)
    {
        $src = null;
        $classes = count($classes) > 0 ? 'class="' . implode(' ', $classes) . '"' : '';

        if (strpos($url, 'youtu') > -1) {
            if (method_exists('\Municipio\Admin\UI\Editor', 'oembed') && (defined('MUNICIPIO_GOOGLEAPIS_KEY') && MUNICIPIO_GOOGLEAPIS_KEY)) {
                global $post;
                return \Municipio\Admin\UI\Editor::oembed('', $url, array(), $post->ID, false);
            }

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

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
