<?php
    namespace Modularity;

    class BlockManager {

        public $modules = [];
        public $classes = [];

        public function __construct() {            
            add_filter( 'block_categories', array($this, 'filterCategories'), 10, 2 );
            add_filter('acf/load_field_group', array($this, 'addLocationRule'));
            add_filter( 'allowed_block_types', array($this, 'filterBlockTypes') );
        }

        public function filterBlockTypes($allowedBlocks) {
            $registeredBlocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
            
            foreach($registeredBlocks as $type => $block) {
                if(str_contains($type, 'core/') && $type !== 'core/freeform') {
                    unset($registeredBlocks[$type]);
                }                                   
            }

            return array_keys($registeredBlocks);                        
        }

        public function filterCategories( $categories, $post ) {            
            
            return array_merge(
                $categories,
                array([
                    'slug' => 'modules',
                    'title' => __( 'Modules', 'modularity' ),
                    'icon' => 'wordpress'
                ])
            );
        }
        
        public function registerBlocks() {
            if( function_exists('acf_register_block_type') ) {                                
                foreach($this->classes as $class) {
                    if($class->isBlockCompatible) {
                        acf_register_block_type(array(
                            'name'              => $class->slug,
                            'title'             => __($class->slug),
                            'description'       => __($class->description),
                            'render_callback'   => array($this, 'renderBlock'),
                            'category'          => 'modules',
                            'moduleName'          => $class->slug
                        ));
                    }
                }
            }

        }

        public function addLocationRule($group) {

            $newGroup = $group;

            $enabledModules = \Modularity\ModuleManager::$enabled;  

            if (($key = array_search('mod-table', $enabledModules)) !== false) {
                unset($enabledModules[$key]);
            } 

            foreach($group['location'] as $location) {                
                foreach($location as $locationRule) {
                    $valueIsModule = in_array($locationRule['value'], $enabledModules);  
                    $locationRuleExists = str_contains($locationRule['value'], 'acf/');

                    // If the location rule that we are trying to add already exists, return original group
                    if($locationRuleExists && $locationRule['param'] === 'block') {
                        
                        return $group;
                    }

                    if($valueIsModule && $locationRule['operator'] === '==') {
                                                                        
                        $newGroup['location'][] = [
                            [
                                'param' => 'block',
                                'operator' => '==', 
                                'value' => \str_replace('mod-', 'acf/', $locationRule['value'])
                            ]
                        ];  
                        
                    }                    
                }
            }

            return $newGroup;
        }

        private function setDefaultValues($data, $defaultValues) {
            foreach($data as $key => &$dataPoint) {
                if(empty($dataPoint)) {
                    $isSnakeCased = \str_contains($key, '_');

                    if($isSnakeCased) {
                        $dataPoint = $defaultValues['_' . $key];
                    } else {
                        $key = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
                        $dataPoint = $defaultValues['_' . $key];
                    }
                }
            }

            return $data;
        }

        private function getDefaultValues($blockData) {
            $fieldDefaultValues = [];
            foreach($blockData as $key => $dataPoint) {
                if($defaultValue = get_field_object($dataPoint)['default_value']) {
                    $fieldDefaultValues[$key] = $defaultValue;
                }                
            }

            return $fieldDefaultValues;
        }

        public function renderBlock($block) {                            
            $defaultValues = $this->getDefaultValues($block['data']);                            
            $display = new Display();            
            $module = $this->classes[$block['moduleName']];
            $module->data = $block['data'];
            $module->data = $module->data();  
            $module->data = $this->setDefaultValues($module->data, $defaultValues); 
            $view = str_replace('.blade.php', '', $module->template());
            $view = !empty($view) ? $view : $block['moduleName'];       
            $viewData = array_merge(['post_type' => $module->moduleSlug], $module->data);            

            echo  $display->renderView($view, $viewData);
        }
    }