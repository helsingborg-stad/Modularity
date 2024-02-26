<?php

namespace Modularity\Module\Quote;

use Municipio\Helper\Image as ImageHelper;

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
        $data = [];
        $fields = $this->getFields();

        if (is_array($fields)) {
            $data = array_merge($this->getDefaultValues(), $fields);
        }

        if (!empty($data['image'])) {
            $data['image'] = (object) ImageHelper::getImageAttachmentData($data['image'], [500, 500]);
        }
        
        return $data;
    }

    /**
     * @return array
     */
    private function getDefaultValues() {
        return [
            'title'                     => false,
            'content'                   => false,
            'image'                     => false
        ];
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
