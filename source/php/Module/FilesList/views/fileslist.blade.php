@group([
    'classList'     => $filterAboveCard ? ['has-filter-outside-card'] : false,
    'direction'     => 'vertical',
    'attributeList' => [
        'js-filter-container' => $uID
    ]
])
    @if ($isFilterable && $filterAboveCard)
        @card([
            'classList' => ['u-padding__4']
        ])
            @include('partials.filter')
        @endcard
    @endif

    @card([
        'heading'       => false,
        'classList'     => [$classes],
        'attributeList' => [
            'js-filter-container' => $uID,
            'aria-labelledby'     => 'mod-fileslist-' . $ID . '-label'
        ],
        'context'       => 'module.files.list'
    ])
        @if (!$hideTitle && !empty($postTitle))
            <div class="c-card__header"@if ($filterAboveCard) style="border-top-left-radius:0;border-top-right-radius:0;"@endif>
                @typography([
                    'id'      => 'mod-fileslist-' . $ID . '-label',
                    'element' => 'h2',
                    'variant' => 'h4'
                ])
                    {!! $postTitle !!}
                @endtypography
            </div>
        @endif

        @if ($isFilterable && !$filterAboveCard)
            @include('partials.filter')
        @endif

        @collection([
            'sharpTop' => true
        ])
            @foreach ($rows as $row)
                @collection__item([
                    'link'          => $row['href'],
                    'icon'          => $row['icon'],
                    'attributeList' => [
                        'js-filter-item' => ''
                    ]
                ])
                    @typography([
                        'element'       => 'span',
                        'variant'       => 'bold',
                        'attributeList' => [
                            ' js-filter-data' => ''
                        ]
                    ])
                        {{ $row['title'] }} ({{ $row['type'] }}, {{ $row['filesize'] }})
                    @endtypography

                    @if (!empty($row['description']))
                        @typography([
                            'element'       => 'span',
                            'variant'       => 'meta',
                            'attributeList' => [
                                ' js-filter-data' => ''
                            ]
                        ])
                            {{ $row['description'] }}
                        @endtypography
                    @endif
                @endcollection__item
            @endforeach
        @endcollection
    @endcard
@endgroup
