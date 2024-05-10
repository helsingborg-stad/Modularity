<?php

namespace Modularity\Tests\Upgrade\Module\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Module\AcfModuleRepeaterFieldsMigrator;
use WP_Mock;

class AcfModuleRepeaterFieldMigratorTest extends TestCase {

    public function testMigrateRepeaterFieldUpdatesSubFields() {
        $this->getUserFunctions();

        WP_Mock::userFunction('update_sub_field', [
            'return' => true, 
            'times' => 1
        ]);

        $newField = [
            'name'      => 'name', 
            'type'      => 'repeater', 
            'fields'    => [
                'oldFieldName'    => 'newFieldName', 
            ]
        ];

        $oldFieldValue = [
            [
                'oldFieldName' => 'oldFieldValue',
            ]
        ];

        $migrator = new AcfModuleRepeaterFieldsMigrator($newField, $oldFieldValue, 0);
        $result = $migrator->migrate();

        $this->assertTrue($result);
    }

    public function testMigrateRepeaterFieldSkipsMissingSubFields() {
        $this->getUserFunctions();

        $newField = [
            'name'      => 'manual_inputs', 
            'type'      => 'repeater', 
            'fields'    => [
                'oldFieldName'    => 'newFieldName', 
            ]
        ];

        $oldFieldValue = [
            [
                'noMatchingField' => 'oldFieldValue',
            ]
        ];

        $migrator = new AcfModuleRepeaterFieldsMigrator($newField, $oldFieldValue, 0);
        $result = $migrator->migrate();

        $this->assertFalse($result);
    }


    public function testMigrateRepeaterFieldReturnsFalseIfNoSubFields() {
        $this->getUserFunctions();

        $newField = [
            'name'      => 'manual_inputs', 
            'type'      => 'repeater', 
            'fields'    => [
            ]
        ];

        $oldFieldValue = [
            [
                'noMatchingField' => 'oldFieldValue',
            ]
        ];

        $migrator = new AcfModuleRepeaterFieldsMigrator($newField, $oldFieldValue, 0);
        $result = $migrator->migrate();

        $this->assertFalse($result);
    }

    private function getUserFunctions() 
    {
        WP_Mock::userFunction('update_field', ['times' => 1]);
        WP_Mock::userFunction('have_rows', [
            'return_in_order' => [true, true, false], 
            'times' => 2]
        );

        WP_Mock::userFunction('the_row', [
            'return_in_order' => [true, false], 
            'times' => 1]
        );
    }
}