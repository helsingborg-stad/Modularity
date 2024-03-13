<?php

namespace Modularity\Tests\Upgrade\Block\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Block\AcfBlockReplaceAndUpdateFieldMigrator;

class AcfBlockReplaceAndUpdateFieldMigratorTest extends TestCase {

    /**
     * @testdox Removes a field based on the field name
     */
    public function testReplaceAndUpdateField() {
        $blockData = [
            'oldFieldName' => 'value',
            '_oldFieldName' => 'key'
        ];

        $newField = [
            'name'      => 'columns',
            'type'      => 'replaceValue',
            'key'       => 'field_571dfdf50d9da',
            'values'    => [
                'grid-md-12'    => 'o-grid-12',
                'grid-md-6'     => 'o-grid-6',
                'grid-md-4'     => 'o-grid-4',
                'grid-md-3'     => 'o-grid-3',
                'default'       => 'o-grid-4'
            ]
        ];
    
        $migrator = new AcfBlockReplaceAndUpdateFieldMigrator($newField, 'oldFieldName', $blockData);
        $result = $migrator->migrate();

        $this->assertEquals([], $result);
    }
}