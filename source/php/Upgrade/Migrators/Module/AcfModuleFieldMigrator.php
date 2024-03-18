<?php

namespace Modularity\Upgrade\Migrators\Module;

use Modularity\Upgrade\Migrators\MigratorInterface;

class AcfModuleFieldMigrator implements MigratorInterface {

    private $newField;
    private $oldFieldValue;
    private $moduleId;

    public function __construct($newField, $oldFieldValue, $moduleId) {
        $this->newField = $newField;
        $this->oldFieldValue = $oldFieldValue;
        $this->moduleId = $moduleId;
    }

    public function migrate():mixed {
        return update_field($this->newField, $this->oldFieldValue, $this->moduleId);
    }
}