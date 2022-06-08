<?php

namespace Modularity\Module\Script;

class Script extends \Modularity\Module
{
    public $slug = 'script';
    public $supports = array();
    public $isBlockCompatible = false;

    public function init()
    {
        $this->nameSingular = __("Script", 'modularity');
        $this->namePlural = __("Script", 'modularity');
        $this->description = __("Outputs unsanitized code to widget area.", 'modularity');

        //Remove html filter
        add_action('save_post', array($this, 'disableHTMLFiltering'), 5);
    }

    /**
     * Removes the filter of html & script data before save.
     * @var int
     */
    public function disableHTMLFiltering($postId)
    {
        
        //Bail early if not a script module save
        if (get_post_type($postId) !== "mod-" . $this->slug) {
            return;
        }

        //Disable filter temporarirly
        add_filter('acf/allow_unfiltered_html', function ($allow_unfiltered_html) {
            return true;
        });
    }

    public function data() : array
    {
        // $fields = json_decode(json_encode(get_fields($this->ID)));
        $data = array();
        $data['embed'] = get_post_meta($this->ID, 'embed_code', true);
        $data['script_wrap_with'] = get_field('script_wrap_with', $this->ID);

        $placeholder = get_field('embedded_placeholder_image', $this->ID);
        $attachment = wp_get_attachment_image_src($placeholder['ID'], [1000, false]);

        $data['placeholder'] = [
            'url' => $attachment[0],
            'width' => $attachment[1],
            'height' => $attachment[2],
            'alt' => $placeholder['alt']
        ];

        $data['scriptPadding'] = (get_post_meta($this->ID, 'embeded_card_padding', true)) ?
            "u-padding__y--".get_post_meta($this->ID, 'embeded_card_padding', true)." u-padding__x--".
                get_post_meta($this->ID, 'embeded_card_padding', true) : "";
        return $data;
    }

    public function template()
    {
        return $this->data['script_wrap_with'] . '.blade.php';
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
