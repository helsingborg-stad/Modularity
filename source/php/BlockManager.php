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
            $enabledModules = \Modularity\ModuleManager::$enabled;      
            unset($enabledModules['mod-table']);
            
            foreach($group['location'] as $location) {                
                foreach($location as $locationRule) {
                    $valueIsModule = in_array($locationRule['value'], $enabledModules);            
                    if($valueIsModule && $locationRule['operator'] === '==') {
                        $group['location'][] = [
                            [
                                'param' => 'block',
                                'operator' => '==', 
                                'value' => \str_replace('mod-', 'acf/', $locationRule['value'])
                            ]
                        ];  
                        
                    }                    
                }
            }

            return $group;
        }

        public function renderBlock($block) {                    
            $display = new Display();            
            $module = $this->classes[$block['moduleName']];
            $module->data = $block['data'];
            $module->data = $module->data();            
            $view = str_replace('.blade.php', '', $module->template());
            $view = !empty($view) ? $view : $block['moduleName'];       
            $viewData = array_merge(['post_type' => $module->moduleSlug], $module->data);            
            
            echo  $display->renderView($view, $viewData);
        }
    }