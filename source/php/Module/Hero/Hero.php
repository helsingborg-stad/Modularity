<?php

namespace Modularity\Module\Hero;

class Hero extends \Modularity\Module
{
    public $slug = 'hero';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __('Hero', 'modularity');
        $this->namePlural = __('Heros', 'modularity');
        $this->description = __('Outputs a hero', 'modularity');

        //Add full-width capabilty to blocks
        add_filter('Modularity/Block/Settings', array($this, 'blockSettings'), 10, 2);

        //Add full width data to view
        add_filter('Modularity/Block/Data', array($this, 'blockData'), 10, 3);
    }

    public function data() : array
    {
        //Get module data
        $fields = get_fields($this->ID);

        //Type
        $type = $fields['mod_hero_background_type'] ?? 'image';

        //Grab image
        if ('image' == $type) {
            $data = [
                'image' => wp_get_attachment_image_src(
                    $fields['mod_hero_background_image']['id'],
                    [1366, false]
                )[0] ?? false,
                'imageFocus' => [
                    'top' =>  $fields['mod_hero_background_image']['top'],
                    'left' => $fields['mod_hero_background_image']['left']
                ]
            ];
        }

        //Grab video
        if ('video' == $type) {
            $data = [
                'video' => $fields['mod_hero_background_video']
            ];
        }

        //Common fields
        $data['type']           = $type;
        $data['size']           = $fields['mod_hero_size'];
        $data['byline']         = $fields['mod_hero_byline'];
        $data['paragraph']      = $fields['mod_hero_body'];
        $data['backgroundType'] = $data['mod_hero_background_type'] ?? 'image';

        //Defaults
        if (!isset($data['stretch'])) {
            $data['stretch'] = false;
        }

        return $data;
    }

    /**
     * Add full width setting to frontend.
     *
     * @param [array] $viewData
     * @param [array] $block
     * @param [object] $module
     * @return array
     */
    public function blockData($viewData, $block, $module) {
        if ($block['name'] == "acf/hero" && $block['align'] == 'full' && !is_admin()) {
            $viewData['stretch'] = true;
        } else {
            $viewData['stretch'] = false;
        }
        return $viewData;
    }

    /**
     * Allow full-width alignment on hero blocks
     *
     * @param array $data
     * @param string $slug
     * @return array
     */
    public function blockSettings($data, $slug) {
        if (strpos($slug, 'hero') === 0 && isset($data['supports'])) {
            $data['supports']['align'] = ['full'];
        }
        return $data;
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
