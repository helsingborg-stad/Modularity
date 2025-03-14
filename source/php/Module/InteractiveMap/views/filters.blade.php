@element([
    'classList' => [
        'interactive-map__filters-container'
    ],
    'attributeList' => [
        'data-js-interactive-map-filters' => ''
    ]
])
    @if (!empty($buttonFilters))
        @foreach($buttonFilters as $level => $layerGroups)
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
                            'interactive-map__filter-button',
                            'u-display--none'
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
    @if(!empty($selectFilters) && count($selectFilters) > 1)
        @select([
            'options' => $selectFilters,
            'preselected' => $preselectedSelectFilter,
            'required' => true,
            'selectAttributeList' => [
                'data-js-main-filter' => ''
            ]
        ])
        @endselect
    @endif
@endelement