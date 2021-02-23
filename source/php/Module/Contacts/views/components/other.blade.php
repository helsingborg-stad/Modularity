@collection__item([
    'classList' => ['c-collection__other'],
    'attributeList' => [
        'itemprop'  => 'other',
    ]
])

    @if ($contact['other'])
            {!! $contact['other']  !!}
    @endif

@endcollection__item