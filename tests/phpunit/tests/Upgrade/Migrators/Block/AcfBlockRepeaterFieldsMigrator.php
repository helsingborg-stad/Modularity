<?php

namespace Modularity\Tests\Upgrade\Block\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Block\AcfBlockRepeaterFieldsMigrator;

class AcfBlockRepeaterFieldsMigratorTest extends TestCase {

    /**
     * @testdox Migrates the main repeater value and key.
     */
    public function testMigrateMainRepeaterField() {
        
        $newFieldFields = [
            'oldRepeaterItemName' => [
                'name'  => 'newRepeaterItemName', 
                'key'   => 'newRepeaterItemKey',
            ], 
        ];
        $blockData = [
            'oldRepeaterFieldName' => '1',
            '_oldRepeaterFieldKey' => 'key',
        ];

        $migrator = new AcfBlockRepeaterFieldsMigrator('newRepeaterFieldName', 'newRepeaterFieldKey', $newFieldFields, 'oldRepeaterFieldName', $blockData);
        $result = $migrator->migrate();
        
        $this->assertEquals('1', $result['newRepeaterFieldName']);
        $this->assertEquals('newRepeaterFieldKey', $result['_newRepeaterFieldName']);
    }

    /**
     * @testdox adds new repeater sub field key and value from old repeater sub fields
     */
    public function testMigrateRepeaterFields() {
        
        $newFieldFields = [
            'oldRepeaterItemName' => [
                'name'  => 'newRepeaterItemName', 
                'key'   => 'newRepeaterItemKey',
            ], 
        ];
        $blockData = [
            'oldRepeaterFieldName' => '1',
            '_oldRepeaterFieldKey' => 'key',
            'oldRepeaterFieldName_0_oldRepeaterItemName' => 'value',
            '_oldRepeaterFieldName_0_oldRepeaterItemKey' => 'key'
        ];

        $migrator = new AcfBlockRepeaterFieldsMigrator('newRepeaterFieldName', 'newRepeaterFieldKey', $newFieldFields, 'oldRepeaterFieldName', $blockData);
        $result = $migrator->migrate();
        
        $this->assertEquals('value', $result['newRepeaterFieldName_0_newRepeaterItemName']);
        $this->assertEquals('newRepeaterItemKey', $result['_newRepeaterFieldName_0_newRepeaterItemName']);
    }

    /**
     * @testdox migrated nested repeater field keys and values
     */
    public function testMigrateNestedRepeaterFields() {
        
        $newFieldFields = [
            'oldNestedRepeaterFieldName' => [
                'name'  => 'newRepeaterItemName', 
                'key'   => 'newRepeaterItemKey',
                'type' => 'repeater',
                'fields' => [
                    'oldNestedRepeaterItemName' => [
                        'name'  => 'newNestedRepeaterItemName', 
                        'key'   => 'newNestedRepeaterItemKey',
                    ], 
                ]
            ], 
        ];
        
        $blockData = [
            'oldRepeaterFieldName' => '1',
            '_oldRepeaterFieldKey' => 'key',
            'oldRepeaterFieldName_0_oldRepeaterItemName' => '1',
            '_oldRepeaterFieldName_0_oldRepeaterItemKey' => 'key',
            'oldRepeaterFieldName_0_oldNestedRepeaterFieldName' => '1',
            '_oldRepeaterFieldName_0_oldNestedRepeaterFieldKey' => 'key',
            'oldRepeaterFieldName_0_oldNestedRepeaterFieldName_0_oldNestedRepeaterItemName' => 'value',
            '_oldRepeaterFieldName_0_oldNestedRepeaterFieldName_0_oldNestedRepeaterItemKey' => 'key'
        ];
        
        $migrator = new AcfBlockRepeaterFieldsMigrator('newRepeaterFieldName', 'newRepeaterFieldKey', $newFieldFields, 'oldRepeaterFieldName', $blockData);
        $result = $migrator->migrate();

        $this->assertEquals('value', $result['newRepeaterFieldName_0_newRepeaterItemName_0_newNestedRepeaterItemName']);
        $this->assertEquals('newNestedRepeaterItemKey', $result['_newRepeaterFieldName_0_newRepeaterItemName_0_newNestedRepeaterItemName']);
    }

    /**
     * @testdox returns blockData when any faulty value is provided.
     * @dataProvider faultyValuesProvider
     */
    public function testReturnsBlockDataWhenFaultyValuesProvided($newFieldName, $newFieldKey, $newFieldFields, $oldFieldName, $blockData) {
        $migrator = new AcfBlockRepeaterFieldsMigrator($newFieldName, $newFieldKey, $newFieldFields, $oldFieldName, $blockData);
        $result = $migrator->migrate();

        $this->assertEquals(['key' => 'value'], $result);
    }

    public function faultyValuesProvider() {
        return [
            [null, 'key', ['key' => 'value'], 'name', ['key' => 'value']],
            ['name', 'key', null, 'name', ['key' => 'value']],
            ['name', 'key', ['key' => 'value'], null, ['key' => 'value']],
        ];
    }
}