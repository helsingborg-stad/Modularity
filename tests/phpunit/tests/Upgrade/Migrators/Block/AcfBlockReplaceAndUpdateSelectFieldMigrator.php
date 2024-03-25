<?php

namespace Modularity\Tests\Upgrade\Block\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Block\AcfBlockReplaceAndUpdateSelectFieldMigrator;

class AcfBlockReplaceAndUpdateSelectFieldMigratorTest extends TestCase {

    /**
     * @testdox ReplaceAndUpdateSelectFieldMigrator Replaces and updates a field to a new key and custom value.
     */
    public function testReplaceAndUpdateSelectFieldValue() {
        $blockData  = $this->getBlockData();
        $newField   = $this->getNewFieldArray();
    
        $migrator   = new AcfBlockReplaceAndUpdateSelectFieldMigrator($newField, 'oldFieldName', $blockData);
        $result     = $migrator->migrate();

        $this->assertEquals('newValue', $result['newFieldName']);
        $this->assertEquals('key', $result['_newFieldName']);
    }

    /**
     * @testdox ReplaceAndUpdateSelectFieldMigrator Replaces and updates a field to a new key and custom value (using default value)
     */
    public function testReplaceAndUpdateFieldValueWithDefaultValue() {
        $blockData = $this->getBlockData();
        $newField = $this->getNewFieldArray('notMatchingOldValue');

        $migrator = new AcfBlockReplaceAndUpdateSelectFieldMigrator($newField, 'oldFieldName', $blockData);
        $result = $migrator->migrate();

        $this->assertEquals('defaultValue', $result['newFieldName']);
        $this->assertEquals('key', $result['_newFieldName']);
    }    
    
    /**
     * @testdox ReplaceAndUpdateSelectFieldMigrator handles faulty values
     * @dataProvider faultyValuesProvider
     */
    public function testReplaceAndUpdateFieldValueHandlesFaultyValues($newField, $oldFieldName, $blockData) {
        $migrator   = new AcfBlockReplaceAndUpdateSelectFieldMigrator($newField, $oldFieldName, $blockData);
        $result     = $migrator->migrate();

        $this->assertEquals($blockData, $result);
    }

    private function getNewFieldArray($oldValue = 'oldValue') {
        return [
            'name'      => 'newFieldName',
            'type'      => 'replaceValue',
            'key'       => 'key',
            'values'    => [
                $oldValue   => 'newValue',
                'default'   => 'defaultValue'
            ]
        ];
    }
    
    private function getBlockData() {
        return [
            'oldFieldName'  => 'oldValue',
            '_oldFieldName' => 'key'
        ];
    }

    public function faultyValuesProvider() {
        return [
            [null, 'oldFieldName', $this->getBlockData()],
            [$this->getNewFieldArray(), null, $this->getBlockData()],
            [$this->getNewFieldArray(), 'oldFieldName', null]
        ];
    }
}