<?php

namespace Modularity\Upgrade\Migrators;

interface MigratorInterface
{
    public function migrate():mixed;
}