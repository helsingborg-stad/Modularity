@element([
    'classList' => [
        'interactive-map__filters-container'
    ],
    'attributeList' => [
        'data-js-interactive-map-filters-container' => ''
    ]
])
    @if (!empty($buttonFilters))
        @foreach($buttonFilters as $level => $layerGroups)
            @element([
                'classList' => [
                    'interactive-map__filters-buttons'
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
                        ],
                        'icon' => 'check_box_outline_blank',
                    ])
                    @endbutton
                @endforeach
            @endelement
        @endforeach
    @endif
    @if(!empty($selectFilters) && count($selectFilters) > 1)
    @element([
        'classList' => [
            'interactive-map__filters-selects'
        ]
    ])
            @select([
                'options' => $selectFilters,
                'preselected' => $preselectedSelectFilter,
                'required' => true,
                'selectAttributeList' => [
                    'data-js-main-filter' => ''
                ],
                'classList' => [
                    'interactive-map__filter-select'
                ]
            ])
            @endselect
        @endelement
    @endif
@endelement