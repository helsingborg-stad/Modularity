<?php

namespace Modularity\Module\Text;

class Text extends \Modularity\Module
{
    public $slug = 'text';
    public $supports = array('editor');

    public function init()
    {
        $this->nameSingular = __('Text', 'modularity');
        $this->namePlural = __('Texts', 'modularity');
        $this->description = __('Outputs text', 'modularity');
    }

    public function data() : array
    {
        $data = $this->getFields(); 

        if (empty($data['post_content']) && !empty($data['content'])) {
            $data['post_content'] = $data['content'];
        }

        if (empty($this->ID)) {
            $data['ID'] = uniqid();
        }

        if(is_array($data)) {
            return $data; 
        }
        
        return [];
    }
    
    public function template()
    {
        if (!isset($this->data['hide_box_frame']) || !$this->data['hide_box_frame']) {
            return 'box.blade.php';
        }
        return 'article.blade.php';
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
