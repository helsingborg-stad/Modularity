<?php

namespace Modularity\Module\Hero;

class Hero extends \Modularity\Module
{
    public $slug = 'hero';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full']
    );

    public function init()
    {
        $this->nameSingular = __('Hero', 'modularity');
        $this->namePlural = __('Heros', 'modularity');
        $this->description = __('Outputs a hero', 'modularity');
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
                    'top' =>  $fields['mod_hero_background_image']['top'] ?? '50',
                    'left' => $fields['mod_hero_background_image']['left'] ?? '50'
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
        $data['type']               = $type;
        $data['size']               = $fields['mod_hero_size'];
        $data['byline']             = $fields['mod_hero_byline'];
        $data['paragraph']          = $fields['mod_hero_body'];
        $data['backgroundType']     = $data['mod_hero_background_type'] ?? 'image';
        $data['heroView']           = $fields['mod_hero_display_as'] ? $fields['mod_hero_display_as'] : 'default';
        $data['ariaLabel']          = __('Page hero section', 'modularity');
        $data['meta']               = $fields['mod_hero_meta'];
        $data['backgroundColor']    = $fields['mod_hero_background_color'] ? $fields['mod_hero_background_color'] : false;
        $data['textColor']          = $fields['mod_hero_text_color'];
        $data['buttonArgs']         = $this->getButtonArgsFromFields($fields);

        return $data;
    }

    /**
     * Get button args from fields
     * @param  array $fields
     * @return array|null
     */
    private function getButtonArgsFromFields(array $fields)
    {
        $buttonArgs = null;

        if (!empty($fields['mod_hero_button_href'])) {
            $buttonArgs['href'] = $fields['mod_hero_button_href'];
        }

        if (!empty($fields['mod_hero_button_text'])) {
            $buttonArgs['text'] = $fields['mod_hero_button_text'];
            $buttonArgs['classList'] = ['u-margin__top--2'];
            $buttonArgs['color'] = 'primary';
        }

        return $buttonArgs;
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
