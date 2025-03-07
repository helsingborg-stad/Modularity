<?php

namespace Modularity\Module\InteractiveMap;

use Modularity\Helper\WpService;
use WpService\WpService as OriginalWpService;
use Modularity\Module\InteractiveMap\Config\InteractiveMapConfigInterface;

class InteractiveMap extends \Modularity\Module
{
    public $slug = 'interactivemap';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full']
    );
    private ?OriginalWpService $wpService;
    private ?InteractiveMapConfigInterface $config = null;
    private array $lang;

    public function init()
    {
        $this->wpService = WpService::get();

        $this->nameSingular = $this->wpService->__('Interactive map', 'modularity');
        $this->namePlural = $this->wpService->__('Interactive maps', 'modularity');
        $this->description = $this->wpService->__('Outputs an interactive map', 'modularity');
        
        $this->lang = $this->getLang();
    }

    public function data(): array
    {
        $data = [];
        $fields = $this->getFields();
        $data['mapID'] = uniqid('map-');
        $data['mapData'] = $fields['osm'];

        $data['structuredLayerFilters'] = $this->getStructuredLayerFilters($fields['osm']);

        return $data;
    }

    private function getStructuredLayerFilters($field) {
        $data = json_decode($field, true);

        if (
            empty($data['layerGroups']) || 
            (empty($data['layerFilter']) || $data['layerFilter'] === 'false')
        ) {
            return []; 
        }
        
        $layers = $data['layerGroups'];
        $tree = [];
        $lookup = [];

        foreach ($layers as $layer) {
            $lookup[$layer['id']] = $layer;
        }
    
        foreach ($lookup as $id => &$layer) {
            $level = 0;
    
            $parentId = $layer['layerGroup'];
            while (!empty($parentId) && isset($lookup[$parentId])) {
                $level++;
                $parentId = $lookup[$parentId]['layerGroup'];
            }
    
            $tree[$level][] = &$layer;
        }
        
        ksort($tree);
        return $tree;
    }

    private function getLang(): array
    {
        return [
            'no-filter' => $this->wpService->__('No taxonomy filter', 'modularity'),
        ];
    }

    public function script() {
        $this->wpService->wpRegisterScript(
            'mod-interactive-map',
            MODULARITY_URL . '/dist/' . \Modularity\Helper\CacheBust::name('js/mod-interactive-map.js')
        );

        $this->wpService->wpEnqueueScript('mod-interactive-map');
    }

    public function style() {
        $this->wpService->wpRegisterStyle('mod-interactive-map', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('css/interactive-map.css'));

        $this->wpService->wpEnqueueStyle('mod-interactive-map');
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
