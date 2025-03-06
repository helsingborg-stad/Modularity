<?php

namespace Modularity\Module\InteractiveMap\Config;

interface InteractiveMapConfigInterface
{
    public function getStartPosition(): LocationInterface;
    public function getPostType(): ?string;
    public function getTaxonomyFiltering(): ?string;
}