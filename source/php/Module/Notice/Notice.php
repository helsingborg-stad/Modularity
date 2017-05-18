<?php

namespace Modularity\Module\Notice;

class Notice extends \Modularity\Module
{
    public $slug = 'notice';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Notice", 'modularity');
        $this->namePlural = __("Notice", 'modularity');
        $this->description = __("Outputs a notice", 'modularity');
    }

    public function data() : array
    {
        $data = get_fields($this->ID);
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->post_type, $this->args));
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
