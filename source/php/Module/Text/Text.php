<?php

namespace Modularity\Module\Text;

class Text extends \Modularity\Module
{
    public $slug = 'text';
    public $nameSingular = 'Text';
    public $namePlural = 'Texts';
    public $description = 'Outputs a text';
    public $supports = array('editor');

    public function init()
    {
        $this->nameSingular = __('Text', 'modularity');
        $this->namePlural = __('Texts', 'modularity');
        $this->description = __('Outputs text', 'modularity');
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
