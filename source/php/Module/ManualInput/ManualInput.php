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
        $data['context'] = ['module.manual-input.' . $this->template];

        $data['accordionColumnTitles'] = $this->createAccordionTitles($fields['accordion_column_titles'], $fields['accordion_column_marking']);

        if (!empty($fields['manual_inputs'])) {
            foreach ($fields['manual_inputs'] as $input) {
                $data['manualInputs'][] = [
                    'title'     => !empty($input['title']) ? $input['title'] : false,
                    'content'   => !empty($input['content']) ? $input['content'] : false,
                    'link'      => !empty($input['link']) ? $input['link'] : false,
                    'linkText'  => !empty($input['link_text']) ? $input['link_text'] : __("Read more", 'modularity'),
                    'image'     => !empty($input['image']['sizes']['medium_large']) ? [
                        'src'   => $input['image']['sizes']['medium_large'],
                        'alt'   => !empty($input['image']['alt']) ? $input['image']['alt'] : '',
                        ] : [],
                    'imageBeforeContent' => isset($input['image_before_content']) ? $input['image_before_content'] : true,
                    'accordionColumnValues' => !empty($input['accordion_column_values']) ? $input['accordion_column_values'] : false,
                ];
            }
        }


        return $data;
    }

     /**
     * @param array $columnTitles Array of arrays
     * @param string $
     * @return array
     */
    private function createAccordionTitles($accordionColumnTitles = false, $accordionColumnMarker = false) {
        $titles = [];
        if (!empty($accordionColumnTitles) || !empty($accordionColumnMarker)) {
            if (!empty($accordionColumnTitles)) {
                foreach ($accordionColumnTitles as $accordionColumnTitle) {
                    $titles = array_merge(array_values($accordionColumnTitle), $titles);
                }
            }
        }
        // $accordionColumnTitles = [];
        // echo '<pre>' . print_r( $accordionColumTitles, true ) . '</pre>';
        // if (!empty($fields['accordion_column_marking']) || !empty($fields['accordion_column_titles'])) {
        //     $accordionColumnTitles[] = !empty($fields['accordion_column_marking']) ? $fields['accordion_column_marking'] : __("Title", 'modularity');
    
        //     if (!empty($fields['accordion_column_titles'])) {
        //         foreach ($fields['accordion_column_titles'] as $accordionColumnTitle) {
        //             $accordionColumnTitles[] = $accordionColumnTitle['accordion_column_title'];
        //         }
        //     }
        // }

        // return $accordionColumnTitles;
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
