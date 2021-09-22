<?php

namespace Modularity\Module\Hero;

class Hero extends \Modularity\Module
{
    public $slug = 'hero';
    public $supports = array('editor');

    public function init()
    {
        $this->nameSingular = __('Hero', 'modularity');
        $this->namePlural = __('Heros', 'modularity');
        $this->description = __('Outputs a hero', 'modularity');
    }

    public function data() : array
    {
        //Get module data
        $data = get_fields($this->ID);

        //Structure image
        $data['mod_hero_image'] = (object) []; 
        $data['mod_hero_image']->url = wp_get_attachment_image_src($data['mod_hero_background_image']['id'], [1366])[0];
        $data['mod_hero_image']->focus = [
            'top' =>  $data['mod_hero_background_image']['top'], 
            'left' => $data['mod_hero_background_image']['left']
        ]; 

        //Remove old image object
        unset($data['mod_hero_background_image']); 

        //Send to view
        return (array) \Modularity\Helper\FormatObject::camelCase($data); 
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
