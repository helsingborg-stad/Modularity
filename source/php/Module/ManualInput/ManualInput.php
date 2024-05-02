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
            foreach ($fields['manual_inputs'] as $index => &$input) {
                $input = array_filter($input, function($value) {
                    return !empty($value) || $value === false;
                });

                $arr                            = array_merge($this->getManualInputDefaultValues(), $input);
                $arr['image']                   = $this->getImageData($arr['image'], $imageSize);
                $arr['accordion_column_values'] = $this->createAccordionTitles($arr['accordion_column_values'], $arr['title']);
                $arr['view']                    = $this->getInputView($fields, $index);
                $arr['columnSize']              = $this->getInputColumnSize($fields, $index);
                $arr                            = \Municipio\Helper\FormatObject::camelCase($arr);
                $data['manualInputs'][]         = (array) $arr;
            }
        }

        return $data;
    }

    /**
     * @return array Array with default values
     */
    private function getManualInputDefaultValues(): array
    {
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
     * Returns the input view based on the given fields and index.
     *
     * @param array $fields The array of fields.
     * @param int $index The index of the field.
     * @return string The input view.
     */
    private function getInputView(array $fields, int $index): string
    {
        return $this->canBeHighlighted($fields, $index) ? $this->getHighlightedView() : $this->template;
    }

    /**
     * Returns the input column size based on the given fields and index.
     *
     * @param array $fields The array of fields.
     * @param int $index The index of the field.
     * @return string The input column size.
     */
    private function getInputColumnSize(array $fields, int $index): string
    {
        $columnSize = !empty($fields['columns']) ? $fields['columns'] : 'o-grid-4';

        if ($this->canBeHighlighted($fields, $index)) {
            return $this->getHighlightedColumnSize($columnSize) . '@md';
        }
        
        return $columnSize . '@md';
    }

    /**
     * Determines if the input field can be highlighted.
     *
     * @param array $fields The array of input fields.
     * @param int $index The index of the current input field.
     * @return bool Returns true if the input field can be highlighted, false otherwise.
     */
    private function canBeHighlighted(array $fields, int $index) 
    {
        return $index === 0 && !empty($fields['highlight_first_input']) && in_array($this->template, ['card', 'block', 'segment']);
    }

    /**
     * Gets the highlighted column size based on the given column size.
     *
     * @param string $columnSize The column size.
     * @return string The highlighted column size.
     */
    private function getHighlightedColumnSize(string $columnSize): string
    {
        switch ($columnSize) {
            case 'o-grid-6':
                return 'o-grid-12';
            case 'o-grid-4':
                return 'o-grid-8';
            case 'o-grid-3':
                return 'o-grid-6';
            default:
                return $columnSize;
        }
    }

    /**
     * Returns the highlighted view based on the template property.
     *
     * @return string The highlighted view.
     */
    private function getHighLightedView(): string 
    {
        switch ($this->template) {
            case "segment":
                return "segment";
            case "block":
                return "card";
            case "card":
                return "block";
            default:
                return $this->template;
        }
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
        
        return 'base.blade.php';
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
