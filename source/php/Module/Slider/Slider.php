<?php

namespace Modularity\Module\Slider;

class Slider extends \Modularity\Module
{
    public $slug = 'slider';
    public $supports = array();

    public $imageSizes = array(
        'ratio-16-9' => array(1250, false),
        'ratio-10-3' => array(1250, false),
        'ratio-36-7' => array(1800, false),
        'ratio-4-3'  => array(1252, false)
    );

    public $paddingRatios = array(
        'ratio-16-9' => 56.25,
        'ratio-10-3' => 30,
        'ratio-36-7' => 19.44,
        'ratio-4-3'  => 75
    );

    public function init()
    {
        $this->nameSingular = __("Slider", 'modularity');
        $this->namePlural = __("Sliders", 'modularity');
        $this->description = __("Outputs multiple images or videos in a sliding apperance.", 'modularity');

        //Adds backwards compability to when we didn't have focal points
        add_filter('acf/load_value/key=field_56a5ed2f398dc', array($this,'filterDesktopImage'), 10, 3);
    }

    /**
     * Adds backwards compability to sliders created before focal point support. 
     *
     * @param array $field
     * @return array $field
     */
    public function filterDesktopImage($value, $postId, $field) {

        if(!is_array($value) && is_numeric($value) && $field['type'] == "focuspoint") {
            return [
                'id' => $value,
                'top' => "40",
                'left' => "50"
            ]; 
        }

        return $value; 
    }

    public function data() : array
    {

        //Get settings
        $fields = get_fields($this->ID);
        $data = [];

        //Assign settings to objects
        $data['autoslide']  = $fields['slides_autoslide'] ? intval($fields['slides_slide_timeout']) : false;
        $data['ratio']      = preg_replace('/ratio-/', '', $fields['slider_format']);
        $data['wrapAround'] = in_array('wrapAround', $fields['additional_options']);
        $data['title'] = isset($fields['post_title']) ? $fields['post_title'] : '';
        $data['slidesPerPage'] = isset($fields['slides_per_page']) ? $fields['slides_per_page'] : '1';

        //Get slides
        $data['slides'] = $this->prepareSlides($fields);
        $data['id'] = $this->ID;


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
                $currentImageSize = array(1140, false);
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

            // Set link text
            if (empty($slide['link_text'])) {
                $slide['link_text'] = __('Read more', 'modularity');
            }

            // In some cases ACF will return an post-id instead of a link.

            if (isset($slide['link_url'])) {
                if (is_numeric($slide['link_url']) && get_post_status($slide['link_url']) == "publish") {
                    $slide['link_url'] = get_permalink($slide['link_url']);
                }
            }

            $slide['heroStyle'] = false;

            if ($slide['textblock_position'] === 'hero') {
                $slide['heroStyle'] = true;
                $slide['textblock_position'] = 'center';
            }

            if (isset($slide['textblock_position']) && !empty($slide['textblock_position'])  && !is_string($slide['textblock_position'])) {
                $slide['textblock_position'] = 'bottom';
            }

            //Set call to action default value
            $slide['call_to_action'] = false;

            if ($slide['link_style'] === 'button' || $slide['acf_fc_layout'] === 'video') {
                $slide['call_to_action'] = array(
                    'title' => $slide['link_text'],
                    'href' => $slide['link_url']
                );

                //remove link url, instead use CTA
                $slide['link_url'] = false;
            }

            // Replace image alt text with link description
            if (isset($slide['image'])
                && !empty($slide['link_type'])
                && $slide['link_type'] !== 'false'
                && !empty($slide['link_url_description'])) {
                $slide['image']['alt'] = $slide['link_url_description'];
            }

            $focusPoint = false;

            if(array_key_exists('top', $slide['image']) && array_key_exists('left', $slide['image'])) {
                $focusPoint = [
                    'top'   => $slide['image']['top'],
                    'left'  => $slide['image']['left']
                ];
            }

            $slide['focusPoint'] = $focusPoint;

            $slide = (object) $slide;
        }

        return $data['slides'];
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
