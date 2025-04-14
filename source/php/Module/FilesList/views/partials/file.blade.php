@typography([
    'element' => 'span',
    'variant' => 'bold',
    'attributeList' => [
        ' js-filter-data' => ''
    ]
])
    {{ $row['title'] }}
    @if (!empty($row['meta']))
        ({{ implode(', ', $row['meta']) }})
    @endif
@endtypography

@if (!empty($row['description']))
    @typography([
        'element' => 'span',
        'variant' => 'meta',
        'attributeList' => [
            ' js-filter-data' => ''
        ]
    ])
        {{ $row['description'] }}
    @endtypography
@endif
