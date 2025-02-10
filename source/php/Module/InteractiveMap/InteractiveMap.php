<?php

namespace Modularity\Module\InteractiveMap;

use Modularity\Helper\WpService;
use WpService\WpService as OriginalWpService;
use Modularity\Module\InteractiveMap\Admin\AcfFilters;
use Modularity\Module\InteractiveMap\Config\GoogleMapsAcfLocation;
use Modularity\Module\InteractiveMap\Config\InteractiveMapConfig;
use Modularity\Module\InteractiveMap\Config\InteractiveMapConfigInterface;

class InteractiveMap extends \Modularity\Module
{
    public $slug = 'interactivemap';
    public $supports = array();
    private ?OriginalWpService $wpService;
    private ?InteractiveMapConfigInterface $config = null;

    public function init()
    {
        $this->nameSingular = __('Interactive map', 'modularity');
        $this->namePlural = __('Interactive maps', 'modularity');
        $this->description = __('Outputs an interactive map', 'modularity');

        $this->wpService = WpService::get();
        if ($this->wpService && $this->wpService->isAdmin()) {
            new AcfFilters($this->wpService);
        }
    }

    public function data(): array
    {
        $data = [];
        $fields = $this->getFields();
        $this->config = $this->setupConfig($fields);

        return $data;
    }

    private function setupConfig(array $settings): InteractiveMapConfigInterface
    {
        $googleMapsAcfLocation = !empty($settings['interactive_map_start_position']) && is_array($settings['interactive_start_position']) ? $settings['interactive_map_start_position'] : [];
        $postType = !empty($settings['interactive_map_post_type']) ? $settings['interactive_map_post_type'] : null;

        return new InteractiveMapConfig(
            new GoogleMapsAcfLocation($googleMapsAcfLocation)
        );
    }

    public function template(): string
    {
        return 'default.blade.php';
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
