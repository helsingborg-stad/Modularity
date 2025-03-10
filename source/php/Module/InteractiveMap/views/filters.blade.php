@element([
    'attributeList' => [
        'data-js-interactive-map-filters' => ''
    ]
])
    @foreach($structuredLayerFilters as $level => $filters)
        @element([

        ])

            @foreach($filters as $filter)
                @button([
                    'text' => $filter['title'] ?? 'Untitled',
                    'attributeList' => [
                        'data-js-filter' => $filter['id'] ?? '',
                    ]
                ])
                @endbutton
            @endforeach
        @endelement
    @endforeach
@endelement