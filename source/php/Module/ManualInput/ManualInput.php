<?php

namespace Modularity\Module\ManualInput;

class ManualInput extends \Modularity\Module
{
    public $slug = 'manualinput';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Manual Input", 'modularity');
        $this->namePlural = __("Manual Inputs", 'modularity');
        $this->description = __("Creates manual input content.", 'modularity');
    }

    public function data(): array
    {
        $data   = [];
        $fields = $this->getFields();
        $this->template = !empty($fields['display_as']) ? $fields['display_as'] : 'card';

        $data['manualInputs'] = [];
        $data['columns'] = !empty($fields['columns']) ? $fields['columns'] . '@md' : 'o-grid-4@md';

        if (!empty($fields['manual_inputs'])) {
            foreach ($fields['manual_inputs'] as $input) {
                $data['manualInputs'][] = [
                    'title'     => !empty($input['title']) ? $input['title'] : false,
                    'content'   => !empty($input['content']) ? $input['content'] : false,
                    'link'      => !empty($input['link']) ? $input['link'] : false,
                    'context'   => ['module.manual-input.' . $this->template],
                    'image'     => !empty($input['image']['sizes']['medium_large']) ? [
                        'src' => $input['image']['sizes']['medium_large'],
                        'alt' => !empty($input['image']['alt']) ? $input['image']['alt'] : '',
                    ] : []
                ];
            }
        }

        return $data;
    }

    public function template() {
        $path = __DIR__ . "/views/" . $this->template . ".blade.php";

        if (file_exists($path)) {
            return $this->template . ".blade.php";
        }
        
        return 'card.blade.php';
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
