<?php

namespace Modularity;

/**
 * Class App
 *
 * @package Modularity
 */
class Upgrade
{
    private $dbVersion = 3; //The db version we want to achive
    private $dbVersionKey = 'modularity_db_version';
    private $db;

    /**
     * App constructor.
     */
    public function __construct()
    {
        //Development tools
        //WARNING: Do not use in PROD. This will destroy your db.
        /*add_action('init', array($this, 'reset'), 1);
        add_action('init', array($this, 'debugPre'), 5);
        add_action('init', array($this, 'debugAfter'), 20);*/

        //Production hook
        // update_option($this->dbVersionKey, 1);
        add_action('wp', array($this, 'initUpgrade'), 10);

    }

    /**
     * Enable to print stuff you need.
     *
     * @return void
     */
    public function debugAfter()
    {
        echo '<h2>After upgrade</h2>';
        echo '<pre style="overflow: auto; max-height: 50vh; margin: 20px; padding: 10px; border: 2px solid #f00;">';
        print_r(get_theme_mods());
        echo '</pre>';
    }

    /**
     * Enable to print stuff you need.
     *
     * @return void
     */
    public function debugPre()
    {
        echo '<h2>Before upgrade</h2>';
        echo '<pre style="overflow: auto; max-height: 50vh; margin: 20px; padding: 10px; border: 2px solid #f00;">';
        print_r(get_theme_mods());
        echo '</pre>';
    }

    /**
     * Reset db version, in order to run all scripts from the beginning.
     *
     * @return void
     */
    public function reset()
    {
        update_option($this->dbVersionKey, 0);
    }

    /**
     * Upgrade database,
     * when you want to upgrade database,
     * create a new function and increase
     * $this->dbVersion.
     *
     * Method inspiration from WordPress Core.
     *
     * @return boolean
     */
    private function v_1($db): bool
    {
        global $wpdb;
     
        $this->migrateBlockFieldsValueToNewFields('acf/divider', [
                'divider_title' => [
                    'name' => 'custom_block_title', 
                    'key' => 'field_block_title'
                ]
            ]
        );

        /* Removing divider acf title field and adding it as post_title */
        $args = array(
            'post_type' => 'mod-divider',
            'numberposts' => -1
        );
        
        $dividers = get_posts($args);

        if (!empty($dividers)) {
            foreach ($dividers as &$divider) {
                $dividerTitleField = get_field('divider_title', $divider->ID);
  
                if (!empty($dividerTitleField) && is_string($dividerTitleField)) {
                    update_post_meta($divider->ID, 'modularity-module-hide-title', false);
                    wp_update_post([
                        'ID' => $divider->ID,
                        'post_title' => $dividerTitleField
                    ]);
                }
            }
        }
        return true; //Return false to keep running this each time!
    }



    private function v_2($db): bool
    {
        $this->migrateBlockFieldsValueToNewFields('acf/index', [
            'index_columns' => [
                'name' => ['name' => 'columns', 'key' => 'field_65001d039d4c4'],
                'type' => 'replaceValue',
                'values' => [
                    'grid-md-12' => 'o-grid-12',
                    'grid-md-6' => 'o-grid-6',
                    'grid-md-4' => 'o-grid-4',
                    'grid-md-3' => 'o-grid-3',
                    'default' => 'o-grid-4'
                ]
            ], 
            'index' => [
                'type' => 'custom',
                'function' => 'migrateIndexBlockRepeater',
                'name' => [
                    'name' => 'manual_inputs', 
                    'key' => 'field_64ff22b2d91b7'
                ], 
            ]
        ],
        'acf/manualinput');

        $indexModules = $this->getPostType('mod-index');

        $this->migrateAcfFieldsValueToNewFields(
            $indexModules, 
            [
                'index' => [
                    'name' => 'manual_inputs', 
                    'type' => 'custom', 
                    'function' => 'migrateIndexModuleRepeater',
                ],
                'index_columns' => [
                    'name' => 'columns',
                    'type' => 'replaceValue',
                    'values' => [
                        'grid-md-12' => 'o-grid-12',
                        'grid-md-6' => 'o-grid-6',
                        'grid-md-4' => 'o-grid-4',
                        'grid-md-3' => 'o-grid-3',
                        'default' => 'o-grid-4'
                    ]
                ],
            ],
            'mod-manualinput'
        );

        return true; //Return false to keep running this each time!
    }

    private function v_3(): bool {
        $options = get_option('modularity-options');
        if (is_array($options['enabled-modules'])) {
            $options['enabled-modules'][] = "mod-manualinput"; 
        }
        update_option('modularity-options', $options);
        return true;
    }

    
    private function v_4($db): bool 
    {
        $this->migrateBlockFieldsValueToNewFields('acf/manualinput', [
            'index' => [
                'type' => 'removeField',
            ],
            'index_columns' => [
                'type' => 'removeField',
            ]
        ]);

        $manualInputModules = $this->getPostType('mod-manualinput');

        $this->migrateAcfFieldsValueToNewFields($manualInputModules, 
            [
                'index' => [
                    'type' => 'removeField',
                ],
                'index_columns' => [
                    'type' => 'removeField',
                ]
            ]);

        return true; //Return false to keep running this each time!
    }


    private function v_5($db): bool
    {        
        $this->migrateBlockFieldsValueToNewFields('acf/posts', [
                'posts_display_as' => [
                    'name' => ['name' => 'display_as','key' => 'field_64ff23d0d91bf'], 
                    'type' => 'replaceValue', 
                    'values' => [
                        'list' => 'list', 
                        'expandable-list' => 'accordion', 
                        'items' => 'card', 
                        'news' => 'card', 
                        'index' => 'card', 
                        'segment' => 'segment', 
                        'collection' => 'collection', 
                        'features-grid' => 'box', 
                        'grid' => 'block', 
                        'default' => 'card'
                    ],     
                ],
                'data' => [
                    'name' => ['name' => 'manual_inputs', 'key' => 'field_64ff22b2d91b7'], 
                    'type' => 'repeater', 
                    'fields' => [
                        'post_title' => ['name' => 'title', 'key' => 'field_64ff22fdd91b8'], 
                        'post_content' => ['name' => 'content', 'key' => 'field_64ff231ed91b9'],
                        'permalink' => ['name' => 'link', 'key' => 'field_64ff232ad91ba'],
                        'item_icon' => ['name' => 'box_icon', 'key' => 'field_65293de2a26c7'],
                        'image' => ['name' => 'image', 'key' => 'field_64ff2355d91bb'],
                        'column_values' => ['name' => 'accordion_column_values', 'key' => 'field_64ff2372d91bc']
                    ]
                ],
                'posts_list_column_titles' => [
                    'name' => ['name' => 'accordion_column_titles', 'key' => 'field_65005968bbc75'],
                    'type' => 'repeater',
                    'fields' => [
                        'column_header' => ['name' => 'accordion_column_title', 'key' => 'field_65005a33bbc77'], 
                    ]
                ],
                'title_column_label' => [
                    'name' => 'accordion_column_marking', 
                    'key' => 'field_650067ed6cc3c'
                ]
            ],
            'acf/manualinput',
            'postsBlockCondition'
        );

        $postsModules = $this->getPostType('mod-posts');

        $filteredPostsModules = array_filter($postsModules, function ($module) {
            if (!empty($module->ID)) {
                $source = get_field('posts_data_source', $module->ID);
                return !empty($source) && $source == 'input';
            }
            return false;
        });

        $this->migrateAcfFieldsValueToNewFields($postsModules, 
            [
                'post_title' => 'title',
                'post_content' => 'content',
                'data' => [
                    'name' => 'manual_inputs', 
                    'type' => 'repeater', 
                    'fields' => [
                        'post_title' => 'title', 
                        'post_content' => 'content',
                        'column_values' => 'accordion_column_values',
                        'permalink' => 'link',
                        'item_icon' => 'box_icon'
                    ]
                ],
                'posts_columns' => [
                    'name' => 'columns',
                    'type' => 'replaceValue',
                    'values' => [
                        'grid-md-12' => 'o-grid-12',
                        'grid-md-6' => 'o-grid-6',
                        'grid-md-4' => 'o-grid-4',
                        'grid-md-3' => 'o-grid-3',
                        'default' => 'o-grid-4'
                    ]
                ],
                'posts_display_as' => [
                    'name' => 'display_as', 
                    'type' => 'replaceValue', 
                    'values' => [
                        'list' => 'list', 
                        'expandable-list' => 'accordion', 
                        'items' => 'card', 
                        'news' => 'card', 
                        'index' => 'card', 
                        'segment' => 'segment', 
                        'collection' => 'collection', 
                        'features-grid' => 'box', 
                        'grid' => 'block', 
                        'default' => 'card'
                    ]
                ],
                'posts_list_column_titles' => [
                    'name' => 'accordion_column_titles',
                    'type' => 'repeater',
                    'fields' => [
                        'column_header' => 'accordion_column_title', 
                    ]
                ],
                'title_column_label' => 'accordion_column_marking',

            ],
            'mod-manualinput'
        );

        return true; //Return false to keep running this each time!
    }

    private function v_6($db): bool
    {
        $fieldsToRemove = [
            'posts_columns' => [
                'type' => 'removeField'
            ],
            'posts_fields' => [
                'type' => 'removeField'
            ],
            'posts_data_source' => [
                'type' => 'removeField'
            ],
            'data' => [
                'type' => 'removeField'
            ],
            'posts_sort_by' => [
                'type' => 'removeField'
            ],
            'posts_sort_order' => [
                'type' => 'removeField'
            ],
            'posts_taxonomy_filter' => [
                'type' => 'removeField'
            ],
            'show_as_slider' => [
                'type' => 'removeField'
            ],
            'posts_highlight_first' => [
                'type' => 'removeField'
            ],
            'posts_display_as' => [
                'type' => 'removeField'
            ],
            'posts_list_column_titles' => [
                'type' => 'removeField'
            ],
            'taxonomy_display' => [
                'type' => 'removeField'
            ],
            'post_single_show_featured_image' => [
                'type' => 'removeField'
            ],
            'title_column_label' => [
                'type' => 'removeField'
            ],
            'allow_freetext_filtering' => [
                'type' => 'removeField'
            ]
        ];
        $this->migrateBlockFieldsValueToNewFields('acf/manualinput', $fieldsToRemove);

        $manualInputModules = $this->getPostType('mod-manualinput');

        $this->migrateAcfFieldsValueToNewFields($manualInputModules, $fieldsToRemove);

        return true; //Return false to keep running this each time!
    }

    /**
     * Get all posts of a post type
     * 
     * @param string $postType Name of the post type to retrieve
     * @return array Array of posts
     */
    private function getPostType(string $postType) {
        $args = array(
            'post_type' => $postType,
            'numberposts' => -1
        );
        
        $posts = get_posts($args);

        return $posts;
    }

    /**
     * Migrate an old field value to a new field with a different name and updated value.
     *
     * This function is responsible for migrating the value of an old field to a new field with a specified name, replacing the old value with the updated value.
     *
     * @param array $newField An array of field data containing the keys "name" (string) and "values" (array).
     * The "values" array should be in the format [oldValue => updatedValue].
     * @param string $oldFieldValue The value of the old field to be replaced.
     * @param int $id The post ID to which the new field value will be associated.
     *
     * @return void
     */
    private function updateAndReplaceFieldValue(array $newField = [], $oldFieldValue, int $id) {
        if (!empty($newField['name']) && !empty($newField['values']) && is_array($newField['values']) && !empty($newField['values'][$oldFieldValue])) { 
            update_field($newField['name'], $newField['values'][$oldFieldValue], $id);
        } else {
            update_field($newField['name'], $newField['values']['default'], $id);
        }
    }

    /**
     * Migrate ACF repeater field values to new fields.
     *
     * This function is responsible for migrating ACF repeater field values to new fields based on the provided mapping
     * and associating these values with a specific post ID.
     *
     * @param array $newField An array describing the new ACF field, including name, type, and subfields.
     * @param array $oldFieldValue The value of the old ACF repeater field.
     * @param int $id The post ID to which the new field values will be associated.
     *
     * @return void
     */
    private function migrateAcfRepeater(array $newField = [], array $oldFieldValue = [], int $id) {
        update_field($newField['name'], $oldFieldValue, $id);
        $subFields = $newField['fields'];
        if (!empty($subFields) && is_array($subFields) && have_rows($newField['name'], $id)) {
            $i = 0;
            while (have_rows($newField['name'], $id)) {
                the_row();
                $i++;
                foreach ($subFields as $oldFieldName => $newFieldName) {
                    if (!empty($oldFieldValue[$i - 1]) && !empty($oldFieldValue[$i - 1][$oldFieldName])) {
                        $oldSubFieldValue = $oldFieldValue[$i - 1][$oldFieldName];
                        if (!empty($oldSubFieldValue)) {
                            update_sub_field([$newField['name'], $i, $newFieldName], $oldSubFieldValue, $id);
                        }
                    }
                }
            }
        }
    }

    /**
     * Module: Extract a field value and adds it to another field
     * 
     * @param string $moduleName The name of the post type containing the field
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param string|false $newBlockName renames the block to a different block.
     */
    private function migrateAcfFieldsValueToNewFields(array $modules, array $fields, $newModuleName = false)
    {
        if (!empty($modules) && is_array($modules)) {
            foreach ($modules as &$module) {
                $this->migrateModuleFields($fields, $module->ID);

                //Update post type
                if (!empty($newModuleName)) {
                    $QueryUpdatePostType = $this->db->prepare(
                        "UPDATE " . $this->db->posts . " SET post_type = %s WHERE ID = %d", 
                        $newModuleName, 
                        $module->ID
                    ); 
                    $this->db->query($QueryUpdatePostType); 
                }
            }
        }
    }


    /* TODO: Upgrade then remove */
    private function migrateIndexBlockRepeater($newField, $blockData, $oldFieldName) {
        $newFieldName = $newField['name']['name'];
        $newFieldKey = $newField['name']['key'];
        $blockData[$newFieldName] = $blockData[$oldFieldName];
        $blockData['_' . $newFieldName] = $newFieldKey;
        if (is_array($blockData)) {
            $indexedArrays = [];
        
            foreach ($blockData as $key => $value) {
                if (preg_match('/^index_(\d+)_(.*)/', $key, $matches)) {
                    if (isset($matches[1]) && isset($matches[2])) {
                        $index = $matches[1];
                        $indexedArrays[$index][$matches[2]] = $value;
                    }
                }
            }

            if (!empty($indexedArrays) && is_array($indexedArrays)) {
                foreach ($indexedArrays as $index => $values) {
                    if (!empty($values['link_type'])) {
                        $title = !empty($values['title']) ? $values['title'] : ($values['link_type'] == 'internal' && !empty($values['page']) ? get_the_title($values['page']) : false);
    
                        $content = !empty($values['lead']) ? $values['lead'] : ($values['link_type'] == 'internal' && !empty($values['page']) ? $this->getIndexExcerpt(get_the_content(null, true, $values['page'])) : false);
                        
                        $image = $values['link_type'] == 'internal' && !empty($values['page']) && !empty($values['image_display']) && $values['image_display'] == 'featured' ? get_post_thumbnail_id($values['page']) : (!empty($values['custom_image']) ? $values['custom_image'] : false);

                        $link = $values['link_type'] == 'internal' && !empty($values['page']) ? get_permalink($values['page']) : (!empty($values['link_url']) && $values['link_type'] == 'external' ? $values['link_url'] : false);
                        
                        $blockData[$newFieldName . '_' . $index . '_title'] = $title;
                        $blockData['_' . $newFieldName . '_' . $index . '_title'] = 'field_64ff22fdd91b8';

                        $blockData[$newFieldName . '_' . $index . '_content'] = $content;
                        $blockData['_' . $newFieldName . '_' . $index . '_content'] = 'field_64ff231ed91b9';

                        $blockData[$newFieldName . '_' . $index . '_image'] = $image;
                        $blockData['_' . $newFieldName . '_' . $index . '_image'] = 'field_64ff2355d91bb';

                        $blockData[$newFieldName . '_' . $index . '_link'] = $link;
                        $blockData['_' . $newFieldName . '_' . $index . '_link'] = 'field_64ff232ad91ba';  
                    }
                }
            }
            
            $blockData['display_as'] = 'card';
            $blockData['_display_as'] = 'field_64ff23d0d91bf';
        }

        return $blockData;
    }

    /* TODO: Upgrade then remove */
    private function migrateIndexModuleRepeater(array $newField, array $oldFieldValue = [], $id) {

        update_field('display_as', 'card', $id);
        
        $updateValue = [];
            
        if (!empty($oldFieldValue) && is_array($oldFieldValue)) {
            $updateValue = [];
        
            foreach ($oldFieldValue as $oldInput) {
                $val = [
                    'image_before_content' => false,
                    'content' => !empty($oldInput['lead']) ? $oldInput['lead'] : (!empty($oldInput['page']->post_content) ? $this->getIndexExcerpt($oldInput['page']->post_content) : false),
                    'title' => !empty($oldInput['title']) ? $oldInput['title'] : (!empty($oldInput['page']->post_title) ? $oldInput['page']->post_title : false),
                    'image' => !empty($oldInput['custom_image']['ID']) ? $oldInput['custom_image']['ID'] : false,
                    'link' => !empty($oldInput['link_url']) ? $oldInput['link_url'] : false,
                ];
                
                if (!empty($oldInput['link_type'])) {
                    if ($oldInput['link_type'] == 'internal' && !empty($oldInput['page']->ID)) {
                        $val['link'] = get_permalink($oldInput['page']->ID);
                        if (!empty($oldInput['image_display']) && $oldInput['image_display'] == 'featured') {
                            $val['image'] = get_post_thumbnail_id($oldInput['page']->ID);
                        }   
                    }
                    
                    if ($oldInput['link_type'] == 'unlinked') {
                        $val['link'] = false;
                    }
                }
                
                $updateValue[] = $val;
            }

        update_field($newField['name'], $updateValue, $id);
        }
    }
    /* TODO: Remove after upgrade */
    private function getIndexExcerpt($postContent) {
        $postContent = preg_replace('#</?a(\s[^>]*)?>#i', '', $postContent);
        if (strpos($postContent, "<!--more-->")) {
            return strip_tags(substr($postContent, 0, strpos($postContent, "<!--more-->")));
        }

        $postContent = wp_trim_words(strip_tags($postContent), 55, '...');

        return $postContent;
    }

    /**
     * Post: Extract a field value and adds it to another field
     * 
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param int $id Id of the post
     */
    private function migrateModuleFields(array $fields, int $id) 
    {
        if (!empty($fields) && is_array($fields)) {
            foreach ($fields as $oldFieldName => $newField) {
                $this->migrateModuleField($oldFieldName, $newField, $id);
            }
        }
    }

    /**
     * Migrate a Single Module Field
     *
     * This function migrates a single module field from its old name to its new name for a specific post.
     * Depending on the field type, it performs the necessary migration actions.
     *
     * @param string $oldFieldName The old name of the field being migrated.
     * @param array|string $newField An array or string representing the new field name or migration details.
     * @param int $id The ID of the post where the field is being migrated.
     */
    private function migrateModuleField(string $oldFieldName, $newField, int $id)  {
        $oldFieldValue = get_field($oldFieldName, $id);
        if (!empty($newField['type'])) {
            if ($newField['type'] == 'removeField') {
                $this->removeModuleField($oldFieldName, $id);
            } elseif ($newField['type'] == 'repeater') {
                $this->migrateAcfRepeater($newField, $oldFieldValue, $id);
            } elseif ($newField['type'] == 'replaceValue') {
                $this->updateAndReplaceFieldValue($newField, $oldFieldValue, $id);
            } elseif ($newField['type'] == 'custom' && !empty($newField['function'])) {
                $this->{$newField['function']}($newField, $oldFieldValue, $id);
            }
        } elseif (!empty($oldFieldValue) && is_string($newField)) {
            update_field($newField, $oldFieldValue, $id);
            // $this->removeModuleField($oldFieldName, $id);
        }
    }

    /**
     * Module: Removes a field
     * 
     * @param string $oldFieldName Name of the field
     * @param int $id Id of the post
     */
    private function removeModuleField(string $oldFieldName, int $id) {
        delete_field($oldFieldName, $id);
    }

    /**
     * Block: Extract a field value and adds it to another field.
     * 
     * @param string $pages Pages with the block
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param string|false $newBlockName renames the block to a different block.
     */
    private function migrateBlockFieldsValueToNewFields($blockName, array $fields = [], $newBlockName = false, $blockConditionFunctionName = false) 
    {
        $pages = $this->getPagesFromBlockName($blockName);
        if (!empty($pages) && is_array($pages) && !empty($fields) && is_array($fields)) {
            foreach ($pages as &$page) {
                if ($page->post_type !== 'customize_changeset') {
                    $blocks = parse_blocks($page->post_content);
                    if (!empty($blocks) && !empty($page->ID)) {
                        foreach ($blocks as &$block) {
                            if (!empty($block['blockName']) && $block['blockName'] === $blockName && !empty($block['attrs']['data']) && $this->blockCondition($blockConditionFunctionName, $block)) {
                                $block['attrs']['data'] = $this->migrateBlockFields($fields, $block['attrs']['data']);

                                if (!empty($newBlockName)) {
                                    $block['blockName'] = $newBlockName;
                                    $block['attrs']['name'] = $newBlockName;
                                }
                            }
                        }
    
                        $serializedBlocks = serialize_blocks($blocks); 
                        
                        if (!empty($serializedBlocks)) {
                            $queryUpdateContent = $this->db->prepare(
                                "UPDATE " . $this->db->posts . " SET post_content = %s WHERE ID = %d", 
                                $serializedBlocks, 
                                $page->ID
                            ); 
                            $this->db->query($queryUpdateContent); 
                        }
                    }
                }
            }
        }
    }

    /**
     * Block: Extract a field value and adds it to another field.
     * 
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param array $blockData All the data of the block (the acf fields attached to the block)
     */
    private function migrateBlockFields(array $fields = [], array $blockData) 
    {
        if (!empty($fields) && is_array($fields)) {
            foreach ($fields as $oldFieldName => $newField) {
                if (isset($blockData[$oldFieldName])) {
                    if (is_array($newField) && !empty($newField['type'])) {
                        if ($newField['type'] == 'removeField') {
                            $blockData = $this->removeBlockField($newField, $blockData, $oldFieldName);
                        } elseif ($newField['type'] == 'replaceValue' && isset($newField['values']) && is_array($newField['values'])) {
                            $blockData['_' . $newField['name']['name']] = $newField['name']['key'];
                            $blockData[$newField['name']['name']] = $this->updateAndReplaceBlockFieldValue($newField, $blockData[$oldFieldName]);
                        } elseif ($newField['type'] == 'repeater') {
                            $blockData = $this->migrateBlockRepeater($newField, $blockData, $oldFieldName);
                        } elseif ($newField['type'] == 'custom' && !empty($newField['function'])) {
                            $blockData = $this->{$newField['function']}($newField, $blockData, $oldFieldName);
                        }
                    } else {
                        $blockData[$newField['name']] = $blockData[$oldFieldName];
                        $blockData['_' . $newField['name']] = $newField['key'];
                    }
                }
            }
        }
        return $blockData;
    }

    private function removeBlockField($newField, $blockData, $oldFieldName) {
        unset($blockData[$oldFieldName]);
        unset($blockData['_' . $oldFieldName]);

        return $blockData;
    }

    /**
     * Migrate a repeater field within a block.
     * 
     * @param array $newField The configuration for the new repeater field.
     * @param array $blockData The data of the block containing the repeater field.
     * @param string $oldFieldName The name of the old repeater field.
     * @return array The updated block data with the migrated repeater field.
     */
    private function migrateBlockRepeater($newField, $blockData, $oldFieldName) {
        $blockData[$newField['name']['name']] = $blockData[$oldFieldName];
        $blockData['_' . $newField['name']['name']] = $newField['name']['key'];
        if (!empty($newField['fields'])) {
            foreach ($newField['fields'] as $oldRepeaterFieldName => $newRepeaterFieldName) {
                if (!empty($newField['name']['name']) && !empty($blockData[$oldFieldName])) {
                    $i = 0;
                    while (isset($blockData[$oldFieldName . '_' . $i . '_' . $oldRepeaterFieldName])) {
                        $blockData[$newField['name']['name'] . '_' . $i . '_' . $newRepeaterFieldName['name']] = $blockData[$oldFieldName . '_' . $i . '_' . $oldRepeaterFieldName];
                        $blockData['_' . $newField['name']['name'] . '_' . $i . '_' . $newRepeaterFieldName['name']] = $newRepeaterFieldName['key'];
                        // unset($blockData[$oldFieldName . '_' . $i . '_' . $oldRepeaterFieldName]);
                        $i++;
                    }
                }
            }
        }
        return $blockData;
    }

    /**
     * Update and replace a block field value based on a configuration.
     * 
     * @param array $newField The configuration for the field update and replacement.
     * @param mixed $oldFieldValue The old field value to be replaced.
     * @return mixed The updated field value.
     */
    private function updateAndReplaceBlockFieldValue($newField, $oldFieldValue) {
        if (isset($newField['values'][$oldFieldValue])) {
            return $newField['values'][$oldFieldValue];
        }

        return $newField['values']['default'];
    }

    /**
     * Check a condition for a block based on a function.
     * 
     * @param string|false $function The name of the condition-checking function.
     * @param array $block The block data to be checked.
     * @return bool Returns true or the condition function.
     */
    private function blockCondition($function, $block) {
        if ($function && method_exists($this, $function)) {
            return $this->$function($block);
        }

        return true;
    }

    /* TODO: Remove after migration. */
    private function postsBlockCondition($block) {
        return !empty($block['attrs']['data']['posts_data_source']) && $block['attrs']['data']['posts_data_source'] == 'input';
    }

    /**
     * Gets all pages that has a specific block attached
     * 
     * @param string $blockName Name of the block
     */
    private function getPagesFromBlockName(string $blockName = '') {
        global $wpdb;
        $pages = $wpdb->get_results(
            "SELECT *
            FROM $wpdb->posts
            WHERE post_content LIKE '%{$blockName}%'"
        );
        
        return $pages;
    }
    
    /**
     * Get all post types
     *
     * @return array
     */
    private function getAllPostTypes()
    {
        $postTypes = array();
        foreach (get_post_types() as $key => $postType) {
            $args = get_post_type_object($postType);

            if (!$args->public || $args->name === 'page') {
                continue;
            }

            $postTypes[$postType] = $args;
        }

        $postTypes['author'] = (object) array(
            'name' => 'author',
            'label' => __('Author'),
            'has_archive' => true,
            'is_author_archive' => true
        );

        return $postTypes;
    }
    

    /**
     * Move and clean out the old theme mod
     *
     * @param string $oldKey
     * @param string $newKey
     * @return bool
     */
    private function migrateThemeMod($oldKey, $newKey, $subkey = null)
    {
        if ($oldValue = get_theme_mod($oldKey)) {
            if ($subkey && isset($oldValue[$subkey])) {
                return $this->setAssociativeThemeMod($newKey, $oldValue[$subkey]);
            } elseif (is_null($subkey)) {
                return $this->setAssociativeThemeMod($newKey, $oldValue);
            }
        }
        return false;
    }

    /**
     * Logs error message
     *
     * @param string $message Error message
     *
     */
    private function logError(string $message)
    {
        error_log($message);
    }

    /**
     * Migrates an ACF option to a theme mod.
     *
     * @param string $option The option key which is being migrated.
     * @param string $themeMod [Optional] The theme mod key to which the
     * option is being migrated. If not provided, it will take the value of $option.
     *
     */
    private function migrateACFOptionToThemeMod(string $option, string $themeMod)
    {
        $errorMessage = "Failed to migrate ACF option \"$option\" to theme mod \"$themeMod\"";

        if (
            !function_exists('get_field') ||
            empty($value = get_field($option, 'option', false)) ||
            !set_theme_mod($themeMod, $value)
        ) {
            $this->logError($errorMessage);
            return;
        }

        delete_field($option, 'option');
    }

    /**
     * Migrates an ACF option image id to a theme mod url.
     *
     * @param string $option The option key which is being migrated.
     * @param string $themeMod [Optional] The theme mod key to which the option is
     * being migrated. If not provided, it will take the value of $option.
     *
     */
    private function migrateACFOptionImageIdToThemeModUrl(string $option, string $themeMod)
    {
        $errorMessage = "Failed to migrate ACF option \"$option\" to theme mod \"$themeMod\"";

        if (!function_exists('get_field')) {
            $this->logError($errorMessage);
            return;
        }

        $value = get_field($option, 'option', false);

        if (empty($value = get_field($option, 'option', false)) || !is_int(absint($value))) {
            $this->logError($errorMessage);
            return;
        }

        $attachmentUrl = wp_get_attachment_url($value);

        if ($attachmentUrl === false || !set_theme_mod($themeMod, $attachmentUrl)) {
            $this->logError($errorMessage);
            return;
        }

        delete_field($option, 'option');
    }

    /**
     * A simple wrapper around set_theme_mod() in order to set a single property value of an associative array setting.
     * Key should include a dot in order to target a property.
     * eg. color_palette.primary will target array('primary' => VALUE).
     *
     * Does not support nested values eg settings.property.nested_value_1.nested_value_2 etc
     *
     * @param string $key
     * @param string $value
     * @param bool $castToArray this will transform existing values which are not arrays to empty arrays when true
     * @return bool True if the value was updated, false otherwise.
     */
    protected function setAssociativeThemeMod($key, $value, $castToArray = false)
    {
        $parsedString = explode('.', $key);
        $key = $parsedString[0] ?? '';
        $property = $parsedString[1] ?? '';

        if (empty($parsedString) || empty($key)) {
            return false;
        }

        if (!empty($property)) {
            $associativeArr = get_theme_mod($key, []);
            $associativeArr = is_array($associativeArr) || $castToArray !== true ? $associativeArr : [];

            if (!is_array($associativeArr)) {
                $errorMessage = "Failed to migrate setting (" . $key . "." . $property . ").
                The specified setting already exists and is not an associative array.";
                $this->logError($errorMessage);
                return false;
            }

            $associativeArr[$property] = $value;
            $value = $associativeArr;
        }

        return set_theme_mod($key, $value);
    }

    /**
     * Deletes a theme mod
     *
     * @param string $key
     * @return bool
     */
    private function deleteThemeMod($key)
    {
        return remove_theme_mod($key);
    }

    /**
     * Undocumented function
     *
     * @param [type] $color
     * @param boolean $opacity
     * @return void
     */
    private function hex2rgba($color, $opacity = false)
    {
        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color)) {
            return $default;
        }

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif (strlen($color) == 3) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    /**
     * Run upgrade functions
     *
     * @return void
     */
    public function initUpgrade()
    {
        if(!is_admin()) {
            return;
        }

        if (empty(get_option($this->dbVersionKey))) {
            update_option($this->dbVersionKey, 0);
        }

        $currentDbVersion = is_numeric(get_option($this->dbVersionKey)) ? (int) get_option($this->dbVersionKey) : 0;

        if ($this->dbVersion != $currentDbVersion) {
            if (!is_numeric($this->dbVersion)) {
                wp_die(__('To be installed database version must be a number.', 'municipio'));
            }

            if (!is_numeric($currentDbVersion)) {
                $this->logError(__('Current database version must be a number.', 'municipio'));
            }

            if ($currentDbVersion > $this->dbVersion) {
                $this->logError(
                    __(
                        'Database cannot be lower than currently installed (cannot downgrade).',
                        'municipio'
                    )
                );
            }
            
            
            //Fetch global wpdb object, save to $db
            $this->globalToLocal('wpdb', 'db');

            //Run upgrade(s)
            while ($currentDbVersion <= $this->dbVersion) {
                $funcName = 'v_' . (string) $currentDbVersion;
                if (method_exists($this, $funcName)) {
                    if ($this->{$funcName}($this->db)) {
                        update_option($this->dbVersionKey, (int) $currentDbVersion);
                        wp_cache_flush();
                    }
                }
                
                $currentDbVersion++;
            }
        }
    }

    /**
     * Creates a local copy of the global instance
     * The target var should be defined in class header as private or public
     * @param string $global The name of global varable that should be made local
     * @param string $local Handle the global with the name of this string locally
     * @return void
     */
    private function globalToLocal($global, $local = null)
    {
        global $$global;

        if (is_null($$global)) {
            return false;
        }

        if (is_null($local)) {
            $this->$global = $$global;
        } else {
            $this->$local = $$global;
        }

        return true;
    }
}
