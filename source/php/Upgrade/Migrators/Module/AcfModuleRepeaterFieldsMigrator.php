<?php

namespace Modularity\Upgrade\Migrators\Module;

use Modularity\Upgrade\Migrators\MigratorInterface;

class AcfModuleRepeaterFieldsMigrator implements MigratorInterface {

    private $newField;
    private $oldFieldValue;
    private $moduleId;

    public function __construct($newField, $oldFieldValue, $moduleId) {
        $this->newField         = $newField;
        $this->oldFieldValue    = $oldFieldValue;
        $this->moduleId         = $moduleId;
    }

    public function migrate() {
        update_field($this->newField['name'], $this->oldFieldValue, $this->moduleId);
        $subFields = $this->newField['fields'];

        if (!$this->repeaterHasSubFields($subFields)) {
            return false;
        }

        return $this->migrateRepeaterSubFields($subFields);
    }

    private function migrateRepeaterSubFields(array $subFields) 
    {
        $i = 0;
        $fieldWasUpdated = false;
        while (have_rows($this->newField['name'], $this->moduleId)) {
            the_row();
            $i++;
            
            foreach ($subFields as $oldFieldName => $newFieldName) {
                $oldSubFieldValue = isset($this->oldFieldValue[$i - 1][$oldFieldName]) ? 
                $this->oldFieldValue[$i - 1][$oldFieldName] : 
                false;
                
                if (!empty($oldSubFieldValue)) {
                    update_sub_field([$this->newField['name'], $i, $newFieldName], $oldSubFieldValue, $this->moduleId);
                    $fieldWasUpdated = true;
                }
            }
        }

        return $fieldWasUpdated;
    }

    private function repeaterHasSubFields($subFields) 
    {
        return 
            !empty($subFields) && 
            is_array($subFields) && 
            have_rows($this->newField['name'], $this->moduleId);
    }
}