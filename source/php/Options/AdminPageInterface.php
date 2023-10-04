<?php

namespace Modularity\Options;

interface AdminPageInterface
{
    public function addHooks(): void;
    public function addAdminPage(): void;
}
