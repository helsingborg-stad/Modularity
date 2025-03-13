@element([
    'classList' => [
        'interactive-map__filters-container'
    ],
    'attributeList' => [
        'data-js-interactive-map-filters' => ''
    ]
])
    @foreach($structuredLayerFilters as $level => $layerGroups)
        @element([
            'attributeList' => [
                'data-js-layer-group-level' => $level,
            ]
        ])
            @foreach($layerGroups as $layerGroup)
                @button([
                    'text' => $layerGroup['title'] ?? 'Untitled',
                    'color' => 'primary',
                    'style' => 'outlined',
                    'toggle' => true,
                    'classList' => [
                        'interactive-map__filter-button'
                    ],
                    'attributeList' => [
                        'data-js-layer-group' => $layerGroup['id'] ?? '',
                    ]
                ])
                @endbutton
            @endforeach
        @endelement
    @endforeach
@endelement