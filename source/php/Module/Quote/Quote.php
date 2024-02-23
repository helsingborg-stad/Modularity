<?php

namespace Modularity\Module\Quote;

class Quote extends \Modularity\Module
{
    public $slug = 'quote';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full']
    );

    public function init()
    {
        $this->nameSingular = __("Quote", 'modularity');
        $this->namePlural = __("Quote", 'modularity');
        $this->description = __("Creates Quotes", 'modularity');
    }

    public function data(): array
    {
        $data = $this->getFields();

        return [];
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
