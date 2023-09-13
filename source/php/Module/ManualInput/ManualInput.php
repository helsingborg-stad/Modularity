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
        $this->template = $this->getTemplateToUse($fields);

        $data['manualInputs'] = [];
        $data['columns'] = !empty($fields['columns']) ? $fields['columns'] . '@md' : 'o-grid-4@md';
        $data['context'] = ['module.manual-input.' . $this->template];
        $data['blockBoxRatio'] = !empty($fields['ratio']) ? $fields['ratio'] : '4:3';
        $data['accordionColumnTitles'] = $this->createAccordionTitles(
            isset($fields['accordion_column_titles']) ? $fields['accordion_column_titles'] : [], 
            isset($fields['accordion_column_marking']) ? $fields['accordion_column_marking'] : ''
        );

        $manualInputDefaultValues = $this->getManualInputDefaultValues();

        if (!empty($fields['manual_inputs']) && is_array($fields['manual_inputs'])) {
            foreach ($fields['manual_inputs'] as $input) {
                $arr = array_merge($this->getManualInputDefaultValues(), $input);
                $arr['image'] = $this->getImage($arr['image']);
                $arr['accordion_column_values'] = $this->createAccordionTitles($arr['accordion_column_values'], $arr['title']);

                if (class_exists('\Municipio\Helper\FormatObject')) {
                    $arr = \Municipio\Helper\FormatObject::camelCase($arr);
                }

                $data['manualInputs'][] = $arr;
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    private function getManualInputDefaultValues() {
        return [
            'title'                     => false,
            'content'                   => false,
            'link'                      => false,
            'link_text'                 => __("Read more", 'modularity'),
            'image'                     =>  [],
            'image_before_content'      => true,
            'accordion_column_values'   => []
        ];
    }

    /**
     * @param array $imageData Data surrounding the image.
     * @return array
     */
    private function getImage($imageData) {
        if (!empty($imageData)) {
            $image = [
                'src' => wp_get_attachment_image_src($imageData['id'], 'full')[0],
                'alt' => !empty($imageData['alt']) ? $imageData['alt'] : false
            ];
            return $image;
        }
        return [];  
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

            if (!empty($accordionColumnTitles) && is_array($accordionColumnTitles)) {
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

    /**
     * @param string $template name of the view
     * @return string
     */
    public function getTemplateToUse($fields) {
        return apply_filters('Modularity/Module/ManualInput/Template', !empty($fields['display_as']) ? $fields['display_as'] : 'card');
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
