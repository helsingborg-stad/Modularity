<?php

namespace Modularity\Upgrade\Migrators;

/**
 * Interface MigratorInterface
 * 
 * This interface defines the contract for migrators.
 */
interface MigratorInterface
{
    /**
     * Perform migration.
     * Blocks: returns the data attached to the block.
     * Modules: Updates the fields using ACF functions.
     * 
     * @return mixed|void 
     */
    public function migrate();
}