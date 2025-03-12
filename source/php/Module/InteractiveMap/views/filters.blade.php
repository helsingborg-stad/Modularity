@element([
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
                    'attributeList' => [
                        'data-js-layer-group' => $layerGroup['id'] ?? '',
                    ]
                ])
                @endbutton
            @endforeach
        @endelement
    @endforeach
@endelement