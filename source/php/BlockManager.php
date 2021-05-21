<?php
    namespace Modularity;

    class BlockManager {

        public $modules = [];

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

        /* public function getCategories($slugs) {
            $categories = [];
            foreach($slugs as $slug) {
                $categorySlug = str_replace('mod-', '', $slug);
                $categoryTitle = ucfirst(str_replace('-', ' ', $categorySlug));
                $categories[] = [
                    'slug' => $categorySlug,
                    'title' => __( $categoryTitle, 'modularity' ),
                    'icon' => 'wordpress'
                ];
            }

            return $categories;
        } */

        public function filterCategories( $categories, $post ) {     
            //$customCategories = $this->getCategories($this->modules);       
            
            return array_merge(
                $categories,
                array([
                    'slug' => 'modules',
                    'title' => __( 'Modules', 'modularity' ),
                    'icon' => 'wordpress'
                ])
            );
        }
        
        public function registerBlock($name) {
        $modules = ['slider', 'notice'];
        
            /* foreach ( $this->modules as $key => &$name) {
                $name = str_replace('mod-', '', $name);               
            } */

            // Register module as a block
            if( function_exists('acf_register_block_type') ) {
                
                    /* $moduleView = MODULARITY_PATH . 'source/php/Module/' . $name . '/views';
                    if(!\file_exists($moduleView)) {
                        continue;
                    }
                    $views = scandir($moduleView); */
                                                            
                    /* foreach($views as $view) {
                        if(!str_contains($view, '.blade.php')) {
                            continue;
                        }
                        $view = str_replace('.blade.php', '', $view); 
    
                    }   */ 
                    
                    
                    acf_register_block_type(array(
                        'name'              => $name,
                        'title'             => __($name),
                        'description'       => __('A custom testimonial block.'),
                        'render_callback'   => array($this, 'renderBlock'),
                        'category'          => 'modules',
                        'postType'          => 'mod-' . $name
                    ));
            }

        }

        public function addLocationRule($group) {
            $enabledModules = \Modularity\ModuleManager::$enabled;            
            
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
            $postType = \Modularity\ModuleManager::prefixSlug($block['title']);
            $class = \Modularity\ModuleManager::$classes[$postType];
            $module = new \Modularity\Module\Notice\Notice();
            $module->data = $block['data'];
            
            //echo '<pre>', print_r($block['data']), '</pre>';
            $viewData = array_merge(['post_type' => $block['postType']], $module->data());
            
            echo  $display->renderView($block['title'], $viewData);
        }
    }