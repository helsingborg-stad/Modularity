@card([
    'heading'       => false,
    'classList'     => [$classes],
    'attributeList' => [
        'js-filter-container'   => $uID,
        "aria-labelledby"       => 'mod-fileslist-' . $ID .'-label',
    ],
    'context' => 'module.files.list'
])
    @if (!$hideTitle && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                'id'      => 'mod-fileslist-' . $ID .'-label',
                'element' => 'h2',
                'variant' => 'h4'
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif

    @if($isFilterable)
    <div class="c-card__body">
        @field([
            'type'          => 'text',
            'attributeList' => [
                'type'              => 'search',
                'name'              => 'search',
                'js-filter-input'   => $uID
            ],
            'label'         => __('Search', 'municipio')
        ])
        @endfield
    </div>
    @endif

    @collection([
        'sharpTop' => true
    ])

        @foreach($rows as $row)
            @collection__item([
                'link'          => $row['href'],
                'icon'          => $row['icon'],
                'attributeList' => [
                    'js-filter-item' => ''
                ]
            ])

                @typography([
                    'element'       => !$hideTitle && !empty($postTitle) ? 'h3' : 'h2',
                    'variant'       => 'body',
                    'attributeList' => [
                       ' js-filter-data' => ''
                    ]
                ])
                    {{ $row['title'] }} ({{ $row['type'] }}, {{ $row['filesize'] }})
                @endtypography

                @if(!empty($row['description']))
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
