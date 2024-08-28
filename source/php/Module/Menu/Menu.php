<?php

namespace Modularity\Module\Menu;

use Modularity\Module\Menu\Acf\Select;
use \Municipio\Helper\Navigation\MenuConstructor;
use Modularity\Module\Menu\Decorator\DataDecorator;
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

    public function data(): array
    {
        $data = [];
        $fields = $this->getFields();

        $displayAs = $fields['mod_menu_display_as'] ?? 'listing';
        $menuConstructorInstance = new MenuConstructor('mod-menu-' . $displayAs);
        $dataDecorator = new DataDecorator($fields);
        
        $data['displayAs'] = $displayAs;
        $data['columns'] = $fields['mod_menu_columns'] ?? 3;
        $data['menu'] = $this->getStructuredMenu($menuConstructorInstance, $fields);

        return $dataDecorator->decorate($data);
    }

    public function setMenuItemData($item, $identifier, $bool)
    {
        if ($identifier === 'mod-menu-listing' && !$item['top_level']) {
            $item['icon'] = ['icon' => 'chevron_right', 'size' => 'md'];
            $item['classList'][] = 'mod-menu__list-item';
        }

        return $item;
    }

    private function getStructuredMenu($menuConstructorInstance, $fields): array
    {
        return $menuConstructorInstance->buildStructuredMenu(
                    $menuConstructorInstance->structureMenuItems(
                        wp_get_nav_menu_items($fields['mod_menu_menu']) ?? []
                    )
                );
    }

    public function style()
    {
        wp_register_style('mod-menu-style', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('css/menu.css'));

        wp_enqueue_style('mod-menu-style');
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
