<?php

namespace Modularity\Module\InteractiveMap\Admin;

use WpService\WpService;

class AcfFilters
{
    private string $placeSchemaName = 'Place';

    public function __construct(private WpService $wpService, private GetTaxonomies $taxonomiesHelper)
    {
        $this->wpService->addFilter('acf/load_field/name=interactive_map_post_type', array($this, 'filterPostTypesBasedOnSchema'));
    }

    public function filterPostTypesBasedOnSchema($field)
    {
        $placePostTypes = \Municipio\SchemaData\Helper\GetSchemaType::getPostTypesFromSchemaType($this->placeSchemaName);

        foreach ($placePostTypes as $postType) {
            $field['choices'][$postType] = ucfirst($postType);
        }

        return $field;
    }
}