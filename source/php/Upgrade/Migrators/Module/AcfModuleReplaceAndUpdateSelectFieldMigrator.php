<?php

namespace Modularity\Upgrade\Migrators\Module;

use Modularity\Upgrade\Migrators\MigratorInterface;

class AcfModuleReplaceAndUpdateSelectFieldMigrator implements MigratorInterface {

    private $newField;
    private $oldFieldValue;
    private $moduleId;

    public function __construct($newField, $oldFieldValue, $moduleId) {
        $this->newField         = $newField;
        $this->oldFieldValue    = $oldFieldValue;
        $this->moduleId         = $moduleId;
    }

    public function migrate() {
        if (!empty($this->newField['values'][$this->oldFieldValue])) { 
            return update_field($this->newField['name'], $this->newField['values'][$this->oldFieldValue], $this->moduleId);
        } 
        
        if(!empty($this->newField['values']['default'])) {
            return update_field($this->newField['name'], $this->newField['values']['default'], $this->moduleId);
        }

        return false;
    }
}