<?php

namespace Modularity\Tests\Upgrade\Module\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Module\AcfModuleRemoveFieldMigrator;
use phpmock\mockery\PHPMockery;
use Mockery;
use WP_Mock;

class AcfModuleRemoveFieldMigratorTest extends TestCase {

    /**
     * @testdox Removes a field based on the field name
     */
    public function testRemoveField() {
        WP_Mock::userFunction('delete_field', ['times' => 1, 'return' => true]);

        $migrator = new AcfModuleRemoveFieldMigrator('oldFieldName', 0);
        $result = $migrator->migrate();

        $this->assertTrue($result);
    }
}