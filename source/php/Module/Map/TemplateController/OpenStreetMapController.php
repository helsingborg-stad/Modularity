<?php

namespace Modularity\Module\Map\TemplateController;

/**
 * Class OpenStreetMap
 * @package Modularity\Module\Map\TemplateController
 */
class OpenStreetMapController implements TemplateControllerInterface
{
    private $templateName = 'openStreetMap';
    /**
     * Add data to the template.
     */
    public function addData(array $data, array $fields): array
    {

        $data['pins'] = array();
        $start = $fields['osm_start_position'];

        if(!empty($fields['osm_markers']) && is_array($fields['osm_markers'])) {
            foreach ($fields['osm_markers'] as $marker) {
                if ($this->hasCorrectPlaceData($marker['position'])) {
                    $pin = array();
                    $pin['lat'] = $marker['position']['lat'];
                    $pin['lng'] = $marker['position']['lng'];
                    $pin['tooltip'] = $this->createMarkerTooltip($marker);

                    array_push($data['pins'], $pin);
                }
            }
        }

        if (!empty($start)) {
            $data['startPosition'] = [
                'lat' => $start['lat'], 
                'lng' => $start['lng'], 
                'zoom' => $start['zoom']
            ];
        }

        return $data;
    }

    /** 
     * The function hasCorrectPlaceData in PHP checks if the given place data is correct.
     */
    private function hasCorrectPlaceData($position): bool {
        return !empty($position) && !empty($position['lat'] && !empty($position['lng']));
    }

    /**
     * The function createMarkerTooltip in PHP creates a tooltip for the given marker.
     */
    private function createMarkerTooltip(array $marker) {
        if (!is_array($marker) || empty($marker)) {
            return [];
        }

        $tooltip = [];
        $tooltip['title'] = $marker['title'] ?? '';
        $tooltip['excerpt'] = $marker['description'] ?? '';
        $tooltip['directions']['label'] = $marker['link_text'] ?? '';
        $tooltip['directions']['url'] = $marker['url'] ?? '';

        return $tooltip;
    }

    /**
     * Check if this controller can handle the given fields.
     */
    public function canHandle(array $fields): bool
    {
        return $fields['map_type'] === $this->templateName;
    }

    /**
     * Get the template name.
     */
    public function getTemplateName(): string
    {
        return $this->templateName;
    }
}