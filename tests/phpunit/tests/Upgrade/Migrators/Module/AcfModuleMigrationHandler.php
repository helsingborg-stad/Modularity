<?php

namespace Modularity\Tests\Upgrade\Module\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Module\AcfModuleMigrationHandler;
use WP_Mock;

class AcfModuleMigrationHandlerTest extends TestCase {

    /**
     * @testdox Returns an array with false when no matching field "type" or couldn't update a field.
     */
    public function testAcfModuleMigrationHandlerReturnsFalseArray() {
        WP_Mock::userFunction('get_field', ['times' => 1, 'return' => true]);
        WP_Mock::userFunction('update_field', ['times' => 1, 'return' => false]);

        $fields = [
            'oldFieldName' => [
                'name'      => 'newFieldName', 
                'type'      => 'unknownType', 
                'fields'    => [
                    'oldFieldName'    => 'newFieldName', 
                ]
            ],
            'oldFieldName2' => 'newFieldName2'
        ];
        
        $migrator = new AcfModuleMigrationHandler($fields, 0);
        $result = $migrator->migrateModuleFields();

        $this->assertEquals([false, false], $result);
    }

    public function testInitiatesCustomMigrationIfItExists() {
        WP_Mock::userFunction('get_field', ['times' => 1, 'return' => 'value']);
        WP_Mock::userFunction('update_field', ['times' => 1, 'return' => true]);

        $fields = [
            'oldFieldName' => [
                'name'      => 'newFieldName', 
                'type'      => 'custom', 
                'class'     => 'AcfModuleIndexRepeaterMigrator',
                'fields'    => [
                    'oldFieldName'    => 'newFieldName', 
                ]
            ],
        ];
        
        $migrator = new AcfModuleMigrationHandler($fields, 0);
        $result = $migrator->migrateModuleFields();

        $this->assertEquals([false], $result);
    }
}