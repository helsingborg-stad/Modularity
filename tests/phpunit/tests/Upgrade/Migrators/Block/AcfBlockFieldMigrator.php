<?php

namespace Modularity\Tests\Upgrade\Block\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Block\AcfBlockFieldMigrator;

class AcfBlockFieldMigratorTest extends TestCase {

    /**
     * @testdox Migrates a field to a new name.
     */
    public function testMigrateField() {
        $blockData = [
            'oldFieldName' => 'value',
            '_oldFieldName' => 'key'
        ];

        $newField = [
            'name' => 'newFieldName',
            'key' => 'newFieldKey'
        ];
    
        $migrator = new AcfBlockFieldMigrator($newField, 'oldFieldName', $blockData);
        $result = $migrator->migrate();

        $this->assertEquals('value', $result['newFieldName']);
        $this->assertEquals('newFieldKey', $result['_newFieldName']);
    }
}