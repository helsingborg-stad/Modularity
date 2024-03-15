<?php

namespace Modularity\Upgrade\Migrators\Module;


class AcfModuleMigrationHandler {

    private $fields;
    private $blockData;

    public function __construct($fields, $blockData) {

        $this->fields = $fields;
        $this->blockData = $blockData;
    }

    public function migrateBlockFields() 
    {

    }
}