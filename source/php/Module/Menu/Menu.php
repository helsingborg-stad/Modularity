<?php

namespace Modularity\Module\Menu;

class Menu extends \Modularity\Module
{
    public $slug = 'menu';
    public $supports = array();
    public $displaySettings = null;


    public function init()
    {
        $this->nameSingular = __('Menu', 'modularity');
        $this->namePlural = __('Menus', 'modularity');
        $this->description = __('Outputs a menu.', 'modularity');
    }

    public function data(): array
    {
        $data = array();

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
