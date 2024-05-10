<?php

namespace Modularity\Tests\Upgrade\Block\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Block\AcfBlockRemoveFieldMigrator;

class AcfBlockRemoveFieldMigratorTest extends TestCase {

    /**
     * @testdox Removes a field based on the field name
     */
    public function testRemoveField() {
        $blockData = [
            'oldFieldName' => 'value',
            '_oldFieldName' => 'key'
        ];
    
        $migrator = new AcfBlockRemoveFieldMigrator('oldFieldName', $blockData);
        $result = $migrator->migrate();

        $this->assertEquals([], $result);
    }

    /**
     * @testdox Returns block data if faulty values
     * @dataProvider faultyValues
     */
    public function testReturnsBlockDataIfFaultyValues($fieldName, $blockData) {

        $migrator = new AcfBlockRemoveFieldMigrator($fieldName, $blockData);
        $result = $migrator->migrate();

        $this->assertEquals($blockData, $result);
    }

    public function faultyValues() {
        return [
            ['fieldName' => '', 'blockData' => []],
            ['fieldName' => 'oldFieldName', 'blockData' => []],
            ['fieldName' => '', 'blockData' => ['oldFieldName' => 'value', '_oldFieldName' => 'key']],
            ['fieldName' => 'oldFieldName', 'blockData' => []],
        ];
    }
}