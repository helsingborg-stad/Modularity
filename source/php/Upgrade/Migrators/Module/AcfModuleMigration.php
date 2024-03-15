<?php

namespace Modularity\Upgrade\Migrators\Module;

use Modularity\Upgrade\Migrators\Module\AcfModuleMigrationHandler;

class AcfModuleMigration {

    private $db;
    private $modules;
    private $fields;
    private $newModuleName;

    public function __construct($db, array $modules, array $fields, $newModuleName = false) {
        $this->modules = $modules;
        $this->fields = $fields;
        $this->newModuleName = $newModuleName;
    }

    /**
     * Block: Extract a field value and adds it to another field.
     * 
     * @param string $pages Pages with the block
     * @param array $fields Fields is an array with the old name of the field being a key and the value being the new name of the field
     * @param string|false $newBlockName renames the block to a different block.
     */
    public function migrateModules() 
    {
        if (!$this->isValidParams()) {
            return;
        }

        foreach ($this->modules as &$module) {
            if (!$module->ID) {
                continue;
            }
            
            // $this->migrateModuleFields($fields, $module->ID);

            //Update post type
            if (!empty($newModuleName)) {
                $this->updateModuleName($module);
            }
        }
    }

    private function updateModuleName($module) {
        $QueryUpdatePostType = $this->db->prepare(
            "UPDATE " . $this->db->posts . " SET post_type = %s WHERE ID = %d", 
            $this->newModuleName, 
            $module->ID
        ); 
        $this->db->query($QueryUpdatePostType); 
    }

    private function isValidParams() {
        return 
            !empty($modules) &&
            !empty($fields);
    }
}