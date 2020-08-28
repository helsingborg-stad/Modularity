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

    public $paddingRatios = array(
        'ratio-16-9' => 56.25,
        'ratio-10-3' => 30,
        'ratio-36-7' => 19.44,
        'ratio-4-3'  => 75
    );

    public $slideColumns;
    public $slideColumnsMobile;

    public $bleed = false;
    public $bleedAmout = array(0.6,0.9,0.8,0.9,0.9);

    public function init()
    {
        $this->nameSingular = __("Slider", 'modularity');
        $this->namePlural = __("Sliders", 'modularity');
        $this->description = __("Outputs multiple images or videos in a sliding apperance.", 'modularity');
    }

    public function data() : array
    {

        //Get settings
        $data = get_fields($this->ID);

        //Toggle of bleed
        if(in_array('allowBleed', (array) $data['additional_options'])) {
            $this->bleed = true;
        }

        //Assign settings to objects
        $data['classes'] = $this->getClasses($data);
        $data['flickity'] = $this->getFlickitySettings($data);

        //Get slides & columns
        $data['slides']         = $this->prepareSlides($data);

        $data['slideColumns']   = $this->slideColumns;

        //Duplicate output of slides if columnize. This is due to bad handlig of flickity [Avoids flickering on first/last slide].
        // if ($this->bleed) {
        //     $data['slides'] = array_merge($data['slides'], $data['slides']);
        // }

        //Calculate slider size (with or without bleed option)
        if ($this->bleed) {
            $data['slideWidth'] = (100/$this->slideColumns) * $this->bleedAmout[$this->slideColumns-1];
            $data['slidePaddingHeight'] = ($this->paddingRatios[$data['slider_format']] / $this->slideColumns) * $this->bleedAmout[$this->slideColumns-1];
            $data['dataBleed'] = true;
        } else {
            $data['slideWidth'] = (100/$this->slideColumns);
            $data['slidePaddingHeight'] = $this->paddingRatios[$data['slider_format']] / $this->slideColumns;
            $data['dataBleed'] = false;
        }

        //Slide cols in smaller resolutions
        $data['slideWidthMobile'] = $data['slideWidth']*2;
        $data['slidePaddingHeightMobile'] = $data['slidePaddingHeight'] * 2;
        $data['slidePaddingHeightDefault'] = $this->paddingRatios[$data['slider_format']];

        //Exception for cicle model
        if ($data['slider_layout'] === 'circle') {
            $data['slider_format'] = null;
            $data['slidePaddingHeight'] = null;
        }

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

            if (isset($slide['show_pause_icon']) && $slide['show_pause_icon'] == true) {
                $slide['slider-show-pause-icon'] = 'slider-show-pause-icon';
            }

            if (isset($slide['pause_icon_transparacy']) && $slide['pause_icon_transparacy'] == true) {
                $slide['slider-icon-transparacy'] = $slide['pause_icon_transparacy'];
            }

            if (isset($slide['show_pause_icon_on_hover']) && $slide['show_pause_icon_on_hover'] == true) {
                $slide['slider-show-on-hover'] = 'slider-show-on-hover';
            }
        }

        return $data['slides'];
    }

    public function getClasses($fields)
    {
        $classes = array();
        $classes[] = 'slider';

        //Navigation placement
        if (isset($fields['navigation_position']) && $fields['navigation_position'] == 'bottom') {
            $classes[] = 'slider-nav-bottom';
        }

        //Navigation behaviour
        if (isset($fields['show_navigation']) && $fields['show_navigation'] == "hover") {
            $classes[] = 'slider-nav-hover';
        }

        //Slider max height class
        if (isset($fields['slider_height']) && $fields['slider_height'] == true) {
            $classes[] = 'slider-height-restrictions';
        }

        if ($fields['slider_layout'] === 'circle') {
            return implode(' ', $classes);
        }

        if ($this->bleed) {
            return implode(' ', $classes);
        }

        if (isset($field['slider_format']) && $field['slider_format']) {
            $classes[] = $field['slider_format'];
        } else {
            $classes[] = 'ratio-1-1-xs';
            $classes[] = 'ratio-4-3-sm';
            $classes[] = 'ratio-16-9';
        }



        return implode(' ', $classes);
    }

    public function getFlickitySettings($fields)
    {

        //Initial number of columns
        $this->slideColumns = isset($fields['slide_columns']) && !empty($fields['slide_columns']) ? (int) $fields['slide_columns'] : 1;

        $flickity = array(
            'cellSelector'   => '.slide',
            'cellAlign'      => $fields['slide_align'] ? $fields['slide_align'] : 'center',
            'wrapAround'     => in_array('wrapAround', (array) $fields['additional_options']),
            'pageDots'       => in_array('pageDots', (array) $fields['additional_options']),
            'freeScroll'     => in_array('freeScroll', (array) $fields['additional_options']),
            'groupCells'     => in_array('groupCells', (array) $fields['additional_options']),
            'setGallerySize' => true,
            'dragThreshold'  => 10
        );

        //Autoslider
        if ($fields['slides_autoslide'] === true) {
            $flickity['autoPlay'] = true;
            $flickity['pauseAutoPlayOnHover'] = true;

            if (!empty($fields['slides_slide_timeout'])) {
                $flickity['autoPlay'] = (int) $fields['slides_slide_timeout'] * 1000;
            }
        }

        //Not enough slides (multiple visible)
        if (count($fields['slides']) <= $this->slideColumns && !$this->bleed) {
            $flickity = array_merge($flickity, array(
                'draggable' => false,
                'pageDots' => false,
                'prevNextButtons' => false,
                'autoPlay' => false,
                'cellAlign' => 'left'
            ));
            $this->slideColumns = count($fields['slides']); //Less slides than specified number of columns avabile
        }

        //Set slide height in js if circular
        if ($fields['slider_layout'] === 'circle') {
            $flickity = array_merge($flickity, array(
                'setGallerySize' => true,
                'resize' => true
            ));
        }

        //Return json
        return json_encode($flickity);
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

            // Get YouTube video ID from url
            $pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";
            preg_match($pattern, $url, $matches);

            if (!isset($matches[1])) {
                return null;
            }

            $src = '<div ' . $classes  . '  style="background-image:url(\'' . (($image !== false) ? $image[0] : '') . '\');"><a data-unavailable="' . __('Video playback unavailable, please activate JavaScript to enable.', 'modularity') .'" href="javascript:void(0)" data-video-id="' . $matches[1] . '"></a></div>';
        } elseif (strpos($url, 'vimeo') > -1) {
            $id = preg_match_all('/.*\/([0-9]+)$/i', $url, $matches);

            if (!isset($matches[1][0])) {
                return null;
            }

            $src = '<div class="player ratio-16-9 ' . $classes  . '"  style="background-image:url(\'' . (($image !== false) ? $image[0] : '') . '\');"><a data-unavailable="' . __('Video playback unavailable, please activate JavaScript to enable.', 'modularity') .'" href="javascript:void(0)" data-video-id="' . $matches[1][0] . '"></a></div>';
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
