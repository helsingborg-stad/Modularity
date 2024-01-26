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
    private $displayAs;
    private $responsiveSize = '@md';

    public function init()
    {
        $this->nameSingular = __("Manual Input", 'modularity');
        $this->namePlural = __("Manual Inputs", 'modularity');
        $this->description = __("Creates manual input content.", 'modularity');

        add_filter('Modularity/Block/Data', array($this, 'blockData'), 50, 3);
    }

    public function data(): array
    {
        $data               = [];
        $fields             = $this->getFields();
        $this->displayAs    = !empty($fields['display_as']) ? $fields['display_as'] : 'card';
        $this->template     = $this->getTemplateToUse($this->displayAs);
      
        $data['context']        = ['module.manual-input.' . $this->template];
        $data['ratio']          = !empty($fields['ratio']) ? $fields['ratio'] : '4:3';
        $imageSize              = $this->getImageSize($this->displayAs);
        $data['manualInputs']   = $this->setupManualInputs($fields, $imageSize);

        $data['accordionColumnTitles'] = $this->createAccordionTitles(
            isset($fields['accordion_column_titles']) ? $fields['accordion_column_titles'] : [], 
            isset($fields['accordion_column_marking']) ? $fields['accordion_column_marking'] : ''
        );
        
        return $data;
    }

    /**
     * Structure and adds data to each manual input.
     * 
     * @param array $fields The ACF field of the module.
     * @param array $imageSize an array containing height and width.
     * 
     * @return array
     */
    private function setupManualInputs(array $fields, array $imageSize) {
        $manualInputs = [];

        if (!empty($fields['manual_inputs']) && is_array($fields['manual_inputs'])) {
            $fields['manual_inputs'] = array_reverse($fields['manual_inputs']);
            foreach ($fields['manual_inputs'] as $index => &$input) {
                $input = array_filter($input, function($value) {
                    return !empty($value) || $value === false;
                });
                $arr                            = array_merge($this->getManualInputDefaultValues(), $input);
                $arr['view']                    = $arr['column_size'] === 'highlight' ? $this->getHighlightedView() : $this->displayAs;
                $arr['column_size']             = $this->getItemColumnSize(
                    $arr['column_size'], 
                    $fields, 
                    $index, 
                    $manualInputs
                );
                $arr['image']                   = $this->getImageData($arr['image'], $imageSize);
                $arr['accordion_column_values'] = $this->createAccordionTitles($arr['accordion_column_values'], $arr['title']);
                $arr                            = \Municipio\Helper\FormatObject::camelCase($arr);
                $manualInputs[] = (array) $arr;
            }
        }

        return array_reverse($manualInputs);
    }

    private function getHighlightedView() {
        switch ($this->displayAs) {
            case 'card':
            case 'block':
                return 'block';
            case 'segment':
                return 'segment';
        }

        return "block";
    }

    /**
     * Gets the correct column size for a manual input item.
     * 
     * @param string $itemColumn A custom column size for the specific item.
     * @param array $fields The ACF field of the module.
     * @param int $index The current manual input index.
     * @param array $manualInputs The current handled manualInputs
     * 
     * @return string
     */
    private function getItemColumnSize(string $itemColumn, array $fields, int $index, array $manualInputs) {
        $defaultColumn = !empty($fields['columns']) ? $fields['columns'] : 'o-grid-4';
        $column = !empty($itemColumn) && $itemColumn !== 'inherit' ? $itemColumn : $defaultColumn;

        if ($column === 'highlight') {
            $column = $this->calculateHighlightedColumnSize($manualInputs, $index);
        }

        return $column . $this->responsiveSize;
    }

    /**
     * Calculate a highlighted posts column size
     * 
     * @param array $fields The ACF field of the module.
     * @param int $index Index of the current manual input
     * 
     * @return string
     */
    private function calculateHighlightedColumnSize(array $manualInputs, int $index) {
        $siblingColumnSize = false;
        if (!empty($manualInputs[$index - 1])) {
            $siblingColumnSize = $manualInputs[$index - 1]['columnSize'];
        }

        if ($siblingColumnSize === 'o-grid-3' . $this->responsiveSize) {
            if (
                !empty($manualInputs[$index - 2]) && 
                $manualInputs[$index - 2]['columnSize'] === 'o-grid-3' . $this->responsiveSize
            ) {
                return 'o-grid-6';
            } else {
                return 'o-grid-9';
            }
        }

        if ($siblingColumnSize === 'o-grid-4' . $this->responsiveSize) {
            return 'o-grid-8';
        }

        return 'o-grid-12';
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
            'box_icon'                  => false,
            'column_size'               => 'inherit'
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
     * @param string $fields The field configuration array.
     * @return string The template name to use for rendering.
     */
    public function getTemplateToUse(string $displayAs) {
        $shouldUseBaseView = ['block', 'box', 'card', 'segment'];
        $templateName = !in_array($displayAs, $shouldUseBaseView) ? $displayAs : 'base'; 
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
