<?php

namespace Modularity\Module\Breadcrumbs;

class Breadcrumbs extends \Modularity\Module
{
    public $slug = 'breadcrumbs';
    public $supports = array();
    public $displaySettings = null;


    public function init()
    {
        $this->nameSingular = __('Breadcrumbs', 'modularity');
        $this->namePlural = __('Breadcrumbs', 'modularity');
        $this->description = __('Outputs the navigational breadcrumb trail to the current page.', 'modularity');
    }

    public function data() : array
    {
        $data = array();
        
        $theme = wp_get_theme('municipio');
        if ($theme->exists()) {
            $municipio = new \Municipio\Controller\BaseController;
            $breadcrumb = new \Municipio\Helper\Navigation('breadcrumb');
            $data['breadcrumbItems'] = $breadcrumb->getBreadcrumbItems($municipio->getPageID());
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