<?php
    namespace Modularity;

    class BlockManager {

        private $postTypes = [];

        public function __construct() {
            add_filter( 'block_categories', array($this, 'filterCategories'), 10, 2 );
            add_filter('acf/load_field_group', array($this, 'addLocationRule'));
        }

        

        function filterCategories( $categories, $post ) {

            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'modules',
                        'title' => __( 'Modules', 'my-plugin' ),
                        'icon'  => 'wordpress',
                    ),
                )
            );
        }
        

        public function registerBlock($name) {
            // Register module as a block
            if( function_exists('acf_register_block_type') ) {
        
                acf_register_block_type(array(
                    'name'              => $name,
                    'title'             => __($name),
                    'description'       => __('A custom testimonial block.'),
                    'render_callback'   => array($this, 'renderBlock'),
                    'category'          => 'modules',
                ));
            }
        }

        /* private function getModuleFieldGroupKey($postType) {
            $groups = acf_get_field_groups(array('post_type' => $postType));
            
            return $groups;
            foreach($groups as $group) {
                foreach($group['location'] as $locationGroups) {
                    foreach($locationGroups as $locationGroup) {
                        if($locationGroup['value'] == $postType || $locationGroup['operator'] == '==') {
                            return $group['key'];
                        }                        
                    }                    
                }
            }
        } */

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
            $this->postTypes[] = $postType;
            $class = \Modularity\ModuleManager::$classes[$postType];
            $module = new \Modularity\Module\Notice\Notice();
            $module->data = $block['data'];
            $viewData = array_merge(['post_type' => $postType], $module->data());
            
            echo  $display->renderView($block['title'], $viewData);
        }

    }