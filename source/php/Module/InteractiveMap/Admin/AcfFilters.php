<?php

namespace Modularity\Module\InteractiveMap\Admin;

use WpService\WpService;

class AcfFilters
{
    private string $placeSchemaName = 'Place';

    public function __construct(private WpService $wpService)
    {
        $this->wpService->addFilter('acf/load_field/key=field_67a9b074d4fc5', array($this, 'filterPostTypesBasedOnSchema'));
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