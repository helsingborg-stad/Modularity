@collection__item([
    'classList' => ['c-collection__opening-hours'],
    'attributeList' => ['itemprop' => 'adress']
])
    @typography(['element' => 'h2', 'variant' => 'h4'])
        {{ $OpeningHours }}
    @endtypography

    @typography([])
        {!! $contact['opening_hours'] !!}
    @endtypography
@endcollection__item
