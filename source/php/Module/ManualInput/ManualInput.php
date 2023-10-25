<?php

namespace Modularity\Module\ManualInput;

use Municipio\Helper\Image as ImageHelper;

class ManualInput extends \Modularity\Module
{
    public $slug = 'manualinput';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full']
    );
    private $template;

    public function init()
    {
        $this->nameSingular = __("Manual Input", 'modularity');
        $this->namePlural = __("Manual Inputs", 'modularity');
        $this->description = __("Creates manual input content.", 'modularity');

        add_filter('Modularity/Block/Data', array($this, 'blockData'), 50, 3);
    }

    public function data(): array
    {
        $data           = [];
        $fields         = $this->getFields();
        $displayAs      = $this->getTemplateToUse($fields);
        $this->template = $displayAs;
        
        $data['manualInputs']   = [];
        $data['columns']        = !empty($fields['columns']) ? $fields['columns'] . '@md' : 'o-grid-4@md';
        $data['context']        = ['module.manual-input.' . $this->template];
        $data['ratio']          = !empty($fields['ratio']) ? $fields['ratio'] : '4:3';
        $imageSize              = $this->getImageSize($displayAs);

        $data['accordionColumnTitles'] = $this->createAccordionTitles(
            isset($fields['accordion_column_titles']) ? $fields['accordion_column_titles'] : [], 
            isset($fields['accordion_column_marking']) ? $fields['accordion_column_marking'] : ''
        );

        if (!empty($fields['manual_inputs']) && is_array($fields['manual_inputs'])) {
            foreach ($fields['manual_inputs'] as $input) {
                $arr                            = array_merge($this->getManualInputDefaultValues(), $input);
                $arr['image']                   = $this->getImageData($arr['image'], $imageSize);
                $arr['accordion_column_values'] = $this->createAccordionTitles($arr['accordion_column_values'], $arr['title']);
                $arr                            = \Municipio\Helper\FormatObject::camelCase($arr);

                $data['manualInputs'][]         = (array) $arr;
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
            'image'                     => false,
            'image_before_content'      => false,
            'accordion_column_values'   => [],
            'box_icon'                  => false
        ];
    }

    /**
     * Get all data attached to the image.
     * 
     * @param array $fields All the acf fields
     * @param array|string $size Array containing height and width OR predefined size as a string.
     * @return array
     */
    private function getImageData($imageId = false, $size = [400, 225])
    {
        if (!empty($imageId)) {
            return ImageHelper::getImageAttachmentData($imageId, $size);
        }

        return false;
    }

    /**
     * Decides the size of the image based on view
     * 
     * @param string $displayAs The name of the template/view.
     * @return array
     */
    private function getImageSize($displayAs) {
        switch ($displayAs) {
            case "segment": 
                return [800, 550];
            case "block":
                return [500, 500];
            case "collection": 
            case "box":
                return [300, 300];
            default: 
                return [400, 225];
        }
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
     * Determine the template to use for rendering based on field configuration.
     *
     * This function calculates the template name to use for rendering based on the
     * provided field configuration. If the 'display_as' key is specified in the
     * configuration and is not empty, it will be used as the template name. Otherwise,
     * the default template name 'card' will be used. The calculated template name is
     * passed through a filter 'Modularity/Module/ManualInput/Template' to allow
     * customization.
     *
     * @param array $fields The field configuration array.
     * @return string The template name to use for rendering.
     */
    public function getTemplateToUse($fields) {
        $templateName = !empty($fields['display_as']) ? $fields['display_as'] : 'card'; 
        return apply_filters(
            'Modularity/Module/ManualInput/Template', 
            $templateName 
        );
    }

    /**
     * Get the template file name for rendering.
     *
     * This function returns the name of the template file to use for rendering
     * based on the template property of the current object. If the specified
     * template file exists, it will be returned; otherwise, a default template
     * ('card.blade.php') will be used.
     *
     * @return string The template file name.
     */
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
