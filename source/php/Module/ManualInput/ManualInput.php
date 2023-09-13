<?php

namespace Modularity\Module\ManualInput;

class ManualInput extends \Modularity\Module
{
    public $slug = 'manualinput';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full']
    );

    public function init()
    {
        $this->nameSingular = __("Manual Input", 'modularity');
        $this->namePlural = __("Manual Inputs", 'modularity');
        $this->description = __("Creates manual input content.", 'modularity');

        add_filter('Modularity/Block/Data', array($this, 'blockData'), 50, 3);
    }

    public function data(): array
    {
        $data   = [];
        $fields = $this->getFields();
        $this->template = !empty($fields['display_as']) ? $fields['display_as'] : 'card';

        $data['manualInputs'] = [];
        $data['columns'] = !empty($fields['columns']) ? $fields['columns'] . '@md' : 'o-grid-4@md';
        $data['context'] = ['module.manual-input.' . $this->template];

        $data['accordionColumnTitles'] = $this->createAccordionTitles(
            isset($fields['accordion_column_titles']) ? $fields['accordion_column_titles'] : [], 
            isset($fields['accordion_column_marking']) ? $fields['accordion_column_marking'] : ''
        );

        $data['blockBoxRatio'] = !empty($fields['ratio']) ? $fields['ratio'] : '4:3';

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
                    'accordionColumnValues' => $this->createAccordionTitles($input['accordion_column_values'], $input['title']),
                ];
            }
        }

        return $data;
    }

     /**
     * @param array $accordionColumnTitles Array of arrays
     * @param string $accordionColumnMarker
     * @return array
     */
    private function createAccordionTitles($accordionColumnTitles = false, $accordionColumnMarker = false) {
        $titles = [];
        if (!empty($accordionColumnTitles) || !empty($accordionColumnMarker)) {
            if (!empty($accordionColumnMarker)) {
                $titles[] = is_string($accordionColumnMarker) ? $accordionColumnMarker : __('Title', 'Modularity');
            }

            if (!empty($accordionColumnTitles)) {
                foreach ($accordionColumnTitles as $accordionColumnTitle) {
                    $titles = array_merge($titles, array_values($accordionColumnTitle));
                }
            }
        }

        return $titles;
    }

    /**
     * Add full width setting to frontend.
     *
     * @param [array] $viewData
     * @param [array] $block
     * @param [object] $module
     * @return array
     */
    public function blockData($viewData, $block, $module) {
        if (strpos($block['name'], "acf/manualinput") === 0 && $block['align'] == 'full' && !is_admin()) {
            $viewData['stretch'] = true;
        } else {
            $viewData['stretch'] = false;
        }
        return $viewData;
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
