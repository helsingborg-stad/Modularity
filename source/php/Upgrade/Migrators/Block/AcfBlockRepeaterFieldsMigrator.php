<?php

namespace Modularity\Upgrade\Migrators\Block;

class AcfBlockRepeaterFieldsMigrator implements Migrator {

    private $newFieldName;
    private $newFieldFields;
    private $oldFieldName;
    private $blockData;

    public function __construct($newFieldName, $newFieldFields, $oldFieldName, $blockData) {
        $this->newFieldName = $newFieldName;
        $this->newFieldFields = $newFieldFields;
        $this->oldFieldName = $oldFieldName;
        $this->blockData = $blockData;
    }

    public function migrate():mixed {

        if (!$this->isValidInputParams($this->newFieldName, $this->newFieldFields, $this->oldFieldName, $this->blockData)) {
            return $this->blockData;
        }

        foreach ($this->newFieldFields as $oldRepeaterFieldName => $newRepeaterFieldName) {

            $i = 0;

            while (isset($this->blockData[$this->oldFieldName . '_' . $i . '_' . $oldRepeaterFieldName])) {
                $newName = $this->newFieldName . '_' . $i . '_' . $newRepeaterFieldName['name'];
                $oldName = $this->oldFieldName . '_' . $i . '_' . $oldRepeaterFieldName;
                $this->blockData[$newName] = $this->blockData[$oldName];
                $this->blockData['_' . $newName] = $newRepeaterFieldName['key'];
                
                if ($this->isNestedRepeaterField($newRepeaterFieldName)) {
                    $migrator = new self($newName, $newRepeaterFieldName['fields'], $oldName, $this->blockData);
                    $this->blockData = $migrator->migrate();
                }
                // unset($blockData[$oldFieldName . '_' . $i . '_' . $oldRepeaterFieldName]);
                $i++;
            }
        }

        return $this->blockData;
    }

    private function isValidInputParams(): bool {
        return
            is_string($this->newFieldName) &&
            is_string($this->oldFieldName) &&
            !empty($this->newFieldName) &&
            !empty($this->oldFieldName) &&
            !empty($this->newFieldFields) &&
            is_array($this->newFieldFields) && 
            is_array($this->blockData);
    }

    private function isNestedRepeaterField(array $newRepeaterFieldName):bool {
        return 
            !empty($newRepeaterFieldName['type']) && 
            $newRepeaterFieldName['type'] === 'repeater' && 
            !empty($newRepeaterFieldName['fields']) && 
            is_array($newRepeaterFieldName['fields']);
    }
}