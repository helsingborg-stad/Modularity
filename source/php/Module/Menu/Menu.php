<?php

namespace Modularity\Module\Menu;

use Modularity\Module\Menu\Acf\Select;
use \Municipio\Helper\Navigation\MenuConstructor as MenuConstructor;
use WP_Post;

class Menu extends \Modularity\Module
{
    public $slug = 'menu';
    public $supports = array();
    public $displaySettings = null;
    private MenuConstructor $menuConstructorInstance;

    public function init()
    {
        $this->nameSingular = __('Menu', 'modularity');
        $this->namePlural = __('Menus', 'modularity');
        $this->description = __('Outputs a menu.', 'modularity');
        $this->menuConstructorInstance = new MenuConstructor();

        new Select();
    }

    public function data(): array
    {
        $fields = $this->getFields();
        $data = [];

        $data['menu'] = $this->getStructuredMenu($fields);
        
        return $data;
    }

    private function getStructuredMenu($fields): array
    {
       return $this->menuConstructorInstance->buildStructuredMenu(
                $this->menuConstructorInstance->structureMenuItems(
                    wp_get_nav_menu_items($fields['menu_menu']) ?? []
                )
            );
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
