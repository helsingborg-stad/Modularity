<?php

namespace Modularity\Upgrade\Migrators\Module;

use Modularity\Upgrade\Migrators\MigratorInterface;

class AcfModuleRemoveFieldMigrator implements MigratorInterface {

    private $fieldName;
    private $moduleId;

    public function __construct($fieldName, $moduleId) {
        $this->fieldName = $fieldName;
        $this->moduleId = $moduleId;
    }

    public function migrate():mixed {
        delete_field($this->fieldName, $this->moduleId);
    }
}