<?php

namespace Modularity\Upgrade\Migrators;

interface Migrator
{
    public function migrate():mixed;
}