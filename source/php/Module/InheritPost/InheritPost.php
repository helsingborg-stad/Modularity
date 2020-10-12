<?php

namespace Modularity\Module\InheritPost;

class InheritPost extends \Modularity\Module
{
    public $slug = 'text';
    public $supports = array('editor');

    public function init()
    {
        $this->nameSingular = __('Post article', 'modularity');
        $this->namePlural =  __('Post articles', 'modularity');
        $this->description = __('Outputs title and content from any post or page', 'modularity');
    }

    public function data() : array
    {
        $data = array();
        $data['inherit'] = get_field('page', $this->ID);

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
