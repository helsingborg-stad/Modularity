@element([
    'classList' => [
        'interactive-map__marker-info-container'
    ],
    'attributeList' => [
        'data-js-interactive-map-marker-info-container' => ''
    ]
])
@slot('before')
    @element([
        'classList' => [
            'interactive-map__marker-info-image'
        ],
        'attributeList' => [
            'data-js-interactive-map-marker-info-image' => ''
        ]
    ])
    @endelement
@endslot
@group([
    'direction' => 'vertical'
])
    @typography([
        'classList' => [
            'interactive-map__marker-title'
        ],
        'attributeList' => [
            'data-js-interactive-map-marker-info-title' => ''
        ]
    ])
    @endtypography
    @typography([
        'classList' => [
            'interactive-map__marker-description'
        ],
        'attributeList' => [
            'data-js-interactive-map-marker-info-description' => ''
        ]
    ])
    @endtypography
@endgroup
    @element([])
    @endelement
@endelement