<?php

namespace Modularity\Upgrade\Migrators\Module;

use Modularity\Upgrade\Migrators\Module\AcfModuleMigrationHandler;

class AcfModuleMigration {

    private $db;
    private $modules;
    private $fields;
    private $newModuleName;

    public function __construct(\wpdb $db, array $modules, array $fields, $newModuleName = false) {
        $this->db = $db;
        $this->modules = $modules;
        $this->fields = $fields;
        $this->newModuleName = $newModuleName;
    }

    public function migrateModules() 
    {
        if (!$this->isValidParams()) {
            return false;
        }

        foreach ($this->modules as &$module) {
            if (!$module->ID) {
                continue;
            }
            
            $migrationFieldManager = new AcfModuleMigrationHandler($this->fields, $module->ID);
            $migrationFieldManager->migrateModuleFields();

            //Update post type
            if (!empty($this->newModuleName)) {
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
            !empty($this->modules) &&
            !empty($this->fields) && 
            !empty($this->db);
    }
}