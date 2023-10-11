<?php

namespace Modularity;

/**
 * Class App
 *
 * @package Modularity
 */
class Upgrade
{
    private $dbVersion = 0; //The db version we want to achive
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
        update_option($this->dbVersionKey, 1);
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
        
        return true; //Return false to keep running this each time!
    }

    /**
     * Module: Extract a field value and adds it to another field
     * 
     * @param string $moduleName The name of the post type containing the field
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param string|false $newBlockName renames the block to a different block.
     */
    private function migrateAcfFieldsValueToNewFields(string $moduleName, array $fields, $newModuleName = false)
    {
        $args = array(
            'post_type' => $moduleName,
            'numberposts' => -1
        );
        
        $modules = get_posts($args);

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
            foreach ($fields as $oldFieldName => $newFieldName) {
                $oldFieldValue = get_field($oldFieldName, $id);
                if (!empty($oldFieldValue)) {
                    update_field($newFieldName, $oldFieldValue, $id);
                }
                delete_field($oldFieldName, $id);
            }
        }
    }

    /**
     * Block: Extract a field value and adds it to another field.
     * 
     * @param string $blockName Name of the block
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param string|false $newBlockName renames the block to a different block.
     */
    private function migrateBlockFieldsValueToNewFields(string $blockName = '', array $fields = [], $newBlockName = false) 
    {
        $pages = $this->getPagesFromBlockName($blockName);

        if (!empty($pages) && is_array($pages) && !empty($fields) && is_array($fields)) {
            foreach ($pages as &$page) {
                if ($page->post_type !== 'customize_changeset') {
                    $blocks = parse_blocks($page->post_content);
    
                    if (!empty($blocks) && !empty($page->ID)) {
                        foreach ($blocks as &$block) {
                            if (!empty($block['blockName']) && $block['blockName'] === $blockName && !empty($block['attrs']['data'])) {
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
            foreach ($fields as $oldFieldName => $newFieldName) {
                if (isset($blockData[$oldFieldName])) {
                    $blockData[$newFieldName] = $blockData[$oldFieldName];
                    unset($blockData[$oldFieldName]);
                }
            }
        }

        return $blockData;
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
                $currentDbVersion++;
                $funcName = 'v_' . (string) $currentDbVersion;
                if (method_exists($this, $funcName)) {
                    if ($this->{$funcName}($this->db)) {
                        update_option($this->dbVersionKey, (int) $currentDbVersion);
                        wp_cache_flush();
                    }
                }
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