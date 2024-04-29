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
        $fields = $this->getFields();
        $data = [];

        //Assign settings to objects
        $data['autoslide']  = $fields['slides_autoslide'] ? intval($fields['slides_slide_timeout']) : false;
        $data['ratio']      = preg_replace('/ratio-/', '', $fields['slider_format']);
        $data['wrapAround'] = in_array('wrapAround', $fields['additional_options']);
        $data['title'] = isset($fields['post_title']) ? $fields['post_title'] : '';
        $data['slidesPerPage'] = isset($fields['slides_per_page']) ? $fields['slides_per_page'] : '1';
        $data['ariaLabels'] =  (object) [
            'prev' => __('Previous slide','modularity'),
            'next' => __('Next slide', 'modularity'),
            'first' => __('Go to first slide', 'modularity'),
            'last' => __('Go to last slide','modularity'),
            'slideX' => __('Go to slide %s', 'modularity'),
        ];

        //Get slides
        if (isset($fields['slides']) && is_array($fields['slides'])) {
            $data['slides'] = array_map([$this, 'prepareSlide'], $fields['slides']);
        }

        $data['id'] = $this->ID;

        //Translations
        $data['lang'] = (object) [
            'noSlidesHeading' => __('Slider is empty','modularity'),
            'noSlides' => __('Please add something to slide.','modularity')
        ]; 


        return $data;
    }

    private function prepareSlide($slide) {
        $slide = $slide['acf_fc_layout'] === 'video' ? 
            $this->prepareVideoSlide($slide) : 
            $this->prepareImageSlide($slide); 

        $slide = $this->getLinkData($slide);

        return $slide;
    }

    private function prepareImageSlide(array $slide) {
        if (!isset($slide['image']['id'])) {
            return null;
        }

        $slide['focusPoint'] = [
            'top' => $slide['image']['top'] ?? "50",
            'left' => $slide['image']['left'] ?? "50"
        ];

        $slide['image'] = \Municipio\Helper\Image::getImageAttachmentData($slide['image']['id'] ?? null, 'full');

        return $slide;
    }    
    
    private function prepareVideoSlide(array $slide) {
        $slide['image'] = \Municipio\Helper\Image::getImageAttachmentData($slide['image'] ?? null, 'full');
        
        return $slide;
    }

    private function getLinkData(array $slide) {
        if (!empty($slide['link_url'])) {
            return $slide;
        }

        if ($this->isValidLinkUrl($slide)) {
            $slide['link_url'] = get_permalink($slide['link_url']);
        }

        // Set link text
        if (empty($slide['link_text'])) {
            $slide['link_text'] = __('Read more', 'modularity');
        }

        $slide['call_to_action'] = false;

        if ($this->isImageLinkType($slide)) {
            $slide['call_to_action'] = array(
                'title' => $slide['link_text'],
                'href' => $slide['link_url']
            );
            //remove link url, instead use CTA
            $slide['link_url'] = false;
        }

        return $slide;
    }

    private function isValidLinkUrl($slide) {
        return 
            isset($slide['link_url']) && 
            is_numeric($slide['link_url']) && 
            get_post_status($slide['link_url']) == "publish";
    }

    private function isImageLinkType($slide) {
        return 
            !empty($slide['link_type']) && 
            $slide['link_type'] !== 'false' && 
            ($slide['link_style'] === 'button' || $slide['acf_fc_layout'] === 'video');
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
