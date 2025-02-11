<?php

namespace Modularity\Module\InteractiveMap\Admin;

use WpService\WpService;

class GetTaxonomies
{
    private string $placeSchemaName = 'Place';

    public function __construct(private WpService $wpService)
    {

    }

    public function getTaxonomies()
    {
        static $taxonomies;
        if ($taxonomies) {
            return $taxonomies;
        }

        $postTypes = \Municipio\SchemaData\Helper\GetSchemaType::getPostTypesFromSchemaType($this->placeSchemaName);

        $taxonomies = [];
        foreach ($postTypes as $postType) {
            $taxonomies[$postType] = $this->getStructuredTaxonomies($postType);
        }

        return $taxonomies;
    }

    private function getStructuredTaxonomies($postType)
    {
        $taxonomies = $this->wpService->getObjectTaxonomies($postType, 'objects');

        $structuredTaxonomies = [];
        foreach ($taxonomies as $taxonomy) {
            $structuredTaxonomies[$taxonomy->name] = $taxonomy->label;
        }

        return $structuredTaxonomies;
    }
}