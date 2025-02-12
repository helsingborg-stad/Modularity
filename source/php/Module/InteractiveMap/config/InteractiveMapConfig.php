<?php

namespace Modularity\Module\InteractiveMap\Config;

class InteractiveMapConfig implements InteractiveMapConfigInterface
{
    public function __construct(
        private LocationInterface $startPosition,
        private ?string $postType,
        private ?string $taxonomyFiltering
    )
    {
    }

    public function getStartPosition(): LocationInterface
    {
        return $this->startPosition;
    }

    public function getPostType(): ?string
    {
        return $this->postType;
    }

    public function getTaxonomyFiltering(): ?string
    {
        return $this->taxonomyFiltering;
    }
}