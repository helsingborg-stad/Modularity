<?php

namespace Modularity\Module\InteractiveMap\Config;

interface InteractiveMapConfigInterface
{
    public function getStartPosition(): LocationInterface;
}