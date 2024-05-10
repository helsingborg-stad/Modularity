<?php

namespace Modularity\Tests\Upgrade\Module\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Module\AcfModuleReplaceAndUpdateSelectFieldMigrator;
use WP_Mock;

class AcfModuleReplaceAndUpdateSelectFieldMigratorTest extends TestCase {

    public function testUpdatesValues() {
        WP_Mock::userFunction('update_field', ['times' => 1, 'return' => true]);

        $newField = [
            'name'      => 'manual_inputs', 
            'type'      => 'replaceValue', 
            'values'    => [
                'oldValue'          => 'newValue', 
            ]
        ];

        $oldFieldValue = 'oldValue';

        $migrator = new AcfModuleReplaceAndUpdateSelectFieldMigrator($newField, $oldFieldValue, 0);
        $result = $migrator->migrate();

        $this->assertTrue($result);
    }

    public function testUpdatesValuesFromDefaultValue() {
        WP_Mock::userFunction('update_field', ['times' => 1, 'return' => true]);
        
        $newField = [
            'name'      => 'manual_inputs', 
            'type'      => 'replaceValue', 
            'values'    => [
                'default' => 'newValue', 
            ]
        ];

        $oldFieldValue = 'oldValue';

        $migrator = new AcfModuleReplaceAndUpdateSelectFieldMigrator($newField, $oldFieldValue, 0);
        $result = $migrator->migrate();

        $this->assertTrue($result);
    }


    public function testDoesNotUpdateValueIfNoMatchingKey() {
        WP_Mock::userFunction('update_field', ['times' => 1]);
        
        $newField = [
            'name'      => 'manual_inputs', 
            'type'      => 'replaceValue', 
            'values'    => [
                'noMatchingValue' => 'newValue', 
            ]
        ];

        $oldFieldValue = 'oldValue';

        $migrator = new AcfModuleReplaceAndUpdateSelectFieldMigrator($newField, $oldFieldValue, 0);
        $result = $migrator->migrate();

        $this->assertFalse($result);
    }
}