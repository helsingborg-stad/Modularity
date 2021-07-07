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

        /**
         * Filter out all of the native block types except freeform, aka classic
         * @return array
         */
        public function filterBlockTypes($allowedBlocks) {
            $registeredBlocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
            
            foreach($registeredBlocks as $type => $block) {
                if(str_contains($type, 'core/') && $type !== 'core/freeform') {
                    unset($registeredBlocks[$type]);
                }                                   
            }

            return array_keys($registeredBlocks);                        
        }

        /**
         * Add a module category
         * @return array
         */
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
        
        /**
         * Register all registered and compatible modules as blocks
         * @return void
         */
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

        /**
         * Add location rule to each field group to make them avaible to corresponding block
         * @return array
         */
        public function addLocationRule($group) {
            $enabledModules = \Modularity\ModuleManager::$enabled;  

            if (($key = array_search('mod-table', $enabledModules)) !== false) {
                unset($enabledModules[$key]);
            } 
                                    
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

        /**
         * Add location rule to each field group to make them avaible to corresponding block
         * @return array
         */
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
            $validatedCorrectly = $this->validateFields($block['data']);

            if($validatedCorrectly) {
                echo  $display->renderView($view, $viewData);
            } else {
                echo '<div class="c-notice c-notice--danger">
                        <span class="c-notice__icon">
                                    
                            <i class="c-icon c-icon--size-md material-icons">
                                report
                            </i>            
                        </span>
                        <span class="c-notice__message--sm">
                            ['. $module->nameSingular .'] Please fill in all required fields
                                    
                        </span>
                    </div>';
            }
        }

        private function validateFields($fields) {        
            $valid = true;

            foreach($fields as $key => $value) {    
                
                if(is_string($key) && is_string($value)) {

                    if(str_contains($key, 'field_')) {
                        $field = $key;
                    }else if(str_contains($value, 'field_')){
                        $field = $value;
                    }

                }
                
                $fieldObject = get_field_object($field);
                
                if($fieldObject['required'] && !$fieldObject['value']) {
                    $valid = false;
                }
                
            }

            return $valid;
        }
    }