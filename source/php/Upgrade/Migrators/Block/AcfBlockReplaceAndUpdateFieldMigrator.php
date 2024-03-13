<?php

namespace Modularity\Upgrade\Migrators\Block;

class AcfBlockReplaceAndUpdateFieldMigrator implements Migrator {

    private $newField;
    private $oldFieldName;
    private $blockData;

    public function __construct($newField, $oldFieldName, $blockData) {

        $this->newField = $newField;
        $this->oldFieldName = $oldFieldName;
        $this->blockData = $blockData;
    }

    public function migrate():mixed {
        $this->blockData['_' . $this->newField['name']] = $this->newField['key'];
        $this->blockData[$this->newField['name']] = $this->getNewValues();

        return $this->blockData;
    }

    private function getNewValues() {
        if (isset($this->newField['values'][$this->blockData[$this->oldFieldName]])) {
            return $this->newField['values'][$this->oldFieldName];
        }

        return $this->newField['values']['default'];
    }
}