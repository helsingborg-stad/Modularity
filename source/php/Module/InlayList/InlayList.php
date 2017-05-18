<?php

namespace Modularity\Module\InlayList;

class InlayList extends \Modularity\Module
{
    public $slug = 'inlaylist';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Inlay List", 'modularity');
        $this->namePlural = __("Inlay Lists", 'modularity');
        $this->description = __("Outputs one or more posts from selected post-type.", 'modularity');
    }

    public function data() : array
    {
        $data = array();
        $data['items'] = get_field('items', $this->ID);
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
