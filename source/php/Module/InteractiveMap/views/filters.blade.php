@element([
    'classList' => [
        'interactive-map__filters-container'
    ],
    'attributeList' => [
        'data-js-interactive-map-filters' => ''
    ]
])
    @if (!empty($subFilters))
        @foreach($subFilters as $level => $layerGroups)
            @element([
                'classList' => [
                    'interactive-map__filters-sub'
                ],
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
    @endif
    @if(!empty($mainFilters))
        @select([
            'options' => $mainFilters,
            'preselected' => $preselectedMainFilter,
            'required' => true,
            'selectAttributeList' => [
                'data-js-main-filter' => ''
            ]
        ])
        @endselect
    @endif
@endelement