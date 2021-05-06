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

    public function init()
    {
        $this->nameSingular = __("Slider", 'modularity');
        $this->namePlural = __("Sliders", 'modularity');
        $this->description = __("Outputs multiple images or videos in a sliding apperance.", 'modularity');
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
        $data['sliderShadow'] = $fields['slider_shadow'];
        $data['title'] = $fields['post_title'];
        
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
            
            // Set link text
            if(empty($slide['link_text'])) {
                $slide['link_text'] = __('Read more', 'modularity');
            }

            // In some cases ACF will return an post-id instead of a link.
            
            if (isset($slide['link_url'])) {
                if (is_numeric($slide['link_url']) && get_post_status($slide['link_url']) == "publish") {
                    $slide['link_url'] = get_permalink($slide['link_url']);
                }
            }

            if(isset($slide['textblock_position']) && !empty($slide['textblock_position'])  && !is_string($slide['textblock_position'])) {
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

            //Use hero styling
            $slide['heroStyle'] = $slide['hero_style'][0] === "true" ? true : false; 

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
