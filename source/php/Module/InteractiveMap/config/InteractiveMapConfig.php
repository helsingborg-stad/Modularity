<?php

namespace Modularity\Module\InteractiveMap\Config;

class InteractiveMapConfig implements InteractiveMapConfigInterface
{
    public function __construct(
        private LocationInterface $startPosition
    )
    {
    }

    public function getStartPosition(): LocationInterface
    {
        return $this->startPosition;
    }
}