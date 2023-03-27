<?php

namespace Modularity\Module\MenuLocation;

class MenuLocation extends \Modularity\Module
{
    public $slug = 'menu-location';
    public $supports = array();
    public $displaySettings = null;

    public function init()
    {
        $this->nameSingular = __('Menu location', 'modularity');
        $this->namePlural = __('Menu locations', 'modularity');
        $this->description = __('Outputs the menu assigned to the selected location.', 'modularity');

        // TODO Unset the default menu location if the module is used on the page:
        // add_filter('theme_mod_nav_menu_locations', array( $this, 'unsetDefaultLocation' ), 0);
        // TODO Print the menu:
    }

    public function unsetDefaultLocation($locations)
    {
        if ($this->hasModule() || has_block('acf/menu-location')) {
        // Get the menu location from the module settings:
            // $location = get_field('menu_location');
            $location = 'quicklinks-menu';
            if (!empty($locations[$location])) {
                unset($locations[$location]);
            }
        }
        return $locations;
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
