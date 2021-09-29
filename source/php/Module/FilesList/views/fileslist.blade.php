@card([
    'heading'       => $postTitle,
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
                'element' => "h4"
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
                    'element'       => 'span',
                    'attributeList' => [
                       ' js-filter-data' => ''
                    ]
                ])
                    {{ $row['title'] }} ({{ $row['type'] }}, {{ $row['filesize'] }})
                @endtypography

            @endcollection__item
        @endforeach
    @endcollection
@endcard
