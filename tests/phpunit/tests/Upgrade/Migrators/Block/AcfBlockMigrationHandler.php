<?php

namespace Modularity\Tests\Upgrade\Block\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Block\AcfBlockMigrationHandler;

class AcfBlockMigrationHandlerTest extends TestCase {

    /**
     * @testdox Calls custom migration classes.
     */
    public function testCallsCustomMigrationsFromClassName() {
        $blockData = [
            'oldFieldName' => 'value',
            '_oldFieldName' => 'key'
        ];
    
        $fields = [
            'oldFieldName' => [
                'name'  => 'newFieldName', 
                'key'   => 'newFieldKey',
                'type' => 'custom',
                'function' => 'AcfMigrateIndexBlockRepeater'
            ]
        ];

        $migrationHandler = new AcfBlockMigrationHandler($fields, $blockData);
        $result = $migrationHandler->migrateBlockFields();

        $this->assertEquals(true, $result);
    }
}