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

    public function init()
    {
        $this->nameSingular = __('Menu', 'modularity');
        $this->namePlural = __('Menus', 'modularity');
        $this->description = __('Outputs a menu.', 'modularity');

        add_filter('Municipio/Navigation/Item', array($this, 'setMenuItemData'), 999, 3);       

        new Select();
    }

    public function setMenuItemData($item, $identifier, $bool)
    {
        if ($identifier === 'mod-menu-list' && !$item['top_level']) {
            $item['icon'] = ['icon' => 'chevron_right', 'size' => 'md'];
        }

        return $item;
    }

    public function data(): array
    {
        $fields = $this->getFields();
        $display = $fields['mod_menu_display'] ?? 'list';
        $menuConstructorInstance = new MenuConstructor('mod-menu-' . $display);
        
        $data = [];
        $data['menu'] = $this->getStructuredMenu($menuConstructorInstance, $fields);
        
        return $data;
    }

    private function getStructuredMenu($menuConstructorInstance, $fields): array
    {
        return $menuConstructorInstance->buildStructuredMenu(
                    $menuConstructorInstance->structureMenuItems(
                        wp_get_nav_menu_items($fields['mod_menu_menu']) ?? []
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
