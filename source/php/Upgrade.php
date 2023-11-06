<?php

namespace Modularity;

/**
 * Class App
 *
 * @package Modularity
 */
class Upgrade
{
    private $dbVersion = 1; //The db version we want to achive
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
                // delete_field('divider_title', $divider->ID);
  
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
                        /* 'post_content' => 'content',
                        'column_values' => 'accordion_column_values',
                        'permalink' => 'link' */
                    ]
                ],
            ],
            'acf/manualinput' /* false */,
            'postsBlockCondition'
        );

        // $this->migrateBlockFieldsValueToNewFields('acf/manualinput', [
        //         'abc' => 'cbd',
        //     ],
        // );

        // $postsModules = $this->getPostType('mod-posts');

        // $filteredPostsModules = array_filter($postsModules, function ($module) {
        //     if (!empty($module->ID)) {
        //         $source = get_field('posts_data_source', $module->ID);
        //         return !empty($source) && $source == 'input';
        //     }
        //     return false;
        // });

        // $this->migrateAcfFieldsValueToNewFields($postsModules, 
        //     [
        //         'post_title' => 'title',
        //         'post_content' => 'content',
        //         'data' => [
        //             'name' => 'manual_inputs', 
        //             'type' => 'repeater', 
        //             'fields' => [
        //                 'post_title' => 'title', 
        //                 'post_content' => 'content',
        //                 'column_values' => 'accordion_column_values',
        //                 'permalink' => 'link'
        //             ]
        //         ],
        //         'posts_columns' => [
        //             'name' => 'columns',
        //             'type' => 'replaceValue',
        //             'values' => [
        //                 'grid-md-12' => 'o-grid-12',
        //                 'grid-md-6' => 'o-grid-6',
        //                 'grid-md-4' => 'o-grid-4',
        //                 'grid-md-3' => 'o-grid-3',
        //                 'default' => 'o-grid-4'
        //             ]
        //         ],
        //         'posts_display_as' => [
        //             'name' => 'display_as', 
        //             'type' => 'replaceValue', 
        //             'values' => [
        //                 'list' => 'list', 
        //                 'expandable-list' => 'accordion', 
        //                 'items' => 'card', 
        //                 'news' => 'card', 
        //                 'index' => 'card', 
        //                 'segment' => 'segment', 
        //                 'collection' => 'collection', 
        //                 'features-grid' => 'box', 
        //                 'grid' => 'block', 
        //                 'default' => 'card'
        //             ]
        //         ]
        //     ]/* ,
        //     'mod-manualinput' */
        // );

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
    private function updateAndReplaceFieldValue(array $newField, string $oldFieldValue, int $id) {
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
     * @param mixed $oldFieldValue The value of the old ACF repeater field.
     * @param int $id The post ID to which the new field values will be associated.
     *
     * @return void
     */
    private function migrateAcfRepeater($newField, $oldFieldValue, $id) {
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

                if (!empty($newModuleName)) {
                    wp_update_post([
                        'ID' => $module->ID,
                        'post_type' => $newModuleName
                    ]);   
                }
            }
        }
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
                $oldFieldValue = get_field($oldFieldName, $id);
                if (!empty($oldFieldValue) && is_array($newField) && !empty($newField['type'])) {
                    if ($newField['type'] == 'repeater') {
                        $this->migrateAcfRepeater($newField, $oldFieldValue, $id);
                    } else if ($newField['type'] == 'replaceValue') {
                        $this->updateAndReplaceFieldValue($newField, $oldFieldValue, $id);
                    }
                } else if (!empty($oldFieldValue) && is_string($newField)) {
                    update_field($newField, $oldFieldValue, $id);
                }
                // delete_field($oldFieldName, $id);
            }
        }
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
                        
                        wp_update_post([
                            'ID' => $page->ID,
                            'post_content' => $serializedBlocks
                        ]);   
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
                        if ($newField['type'] == 'replaceValue' && isset($newField['values']) && is_array($newField['values'])) {
                            $blockData['_' . $newField['name']['name']] = $newField['name']['key'];
                            $blockData[$newField['name']['name']] = $this->updateAndReplaceBlockFieldValue($newField, $blockData[$oldFieldName]);
                        } else if ($newField['type'] == 'repeater') {
                            $blockData = $this->migrateBlockRepeater($newField, $blockData, $oldFieldName);
                        }
                    } else {
                        $blockData[$newField['name']] = $blockData[$oldFieldName];
                        $blockData['_' . $newField['name']] = $newField['key'];
                    }
                    // unset($blockData[$oldFieldName]);
                }
            }
        }
        return $blockData;
    }

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

    private function updateAndReplaceBlockFieldValue($newField, $oldFieldValue) {
        if (isset($newField['values'][$oldFieldValue])) {
            return $newField['values'][$oldFieldValue];
        }

        return $newField['values']['default'];
    }

    private function blockCondition($function, $block) {
        if ($function && method_exists($this, $function)) {
            return $this->$function($block);
        }

        return true;
    }

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
