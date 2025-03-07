@element([
    'attributeList' => [
        'data-js-interactive-map-filters' => ''
    ]
])
    @if (!empty($structuredLayerFilters[0]))
        @element([

        ])

            @foreach($structuredLayerFilters[0] as $filter)
                @dump($filter)
            @endforeach
        @endelement
    @endif
@endelement