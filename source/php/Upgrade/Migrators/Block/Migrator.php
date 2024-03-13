<?php

namespace Modularity\Upgrade\Migrators\Block;

interface Migrator
{
    public function migrate():mixed;
}