<?php

namespace Modularity\Tests\Upgrade\Module\Migrators;

use PHPUnit\Framework\TestCase;
use Modularity\Upgrade\Migrators\Module\AcfModuleFieldMigrator;
use WP_Mock;

class AcfModuleFieldMigratorTest extends TestCase {

    public function testMigrateFieldUpdatesAField() {
        WP_Mock::userFunction('update_field', ['times' => 1, 'return' => true]);

        $migrator = new AcfModuleFieldMigrator('newFieldName', 'oldFieldValue', 0);
        $result = $migrator->migrate();

        $this->assertTrue($result);
    }
}