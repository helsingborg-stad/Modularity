@card([
    'heading' => apply_filters('the_title', $post_title),
    'classList' => [$classes]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element' => "h4"
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @collection([
        'sharpTop' => true,
        'attributeList' => [
            'js-filter-container' => $id
        ]
    ])
        @field([
            'type' => 'text',
            'classList' => [
                'u-margin--2'
            ],
            'attributeList' => [
                'type' => 'search',
                'name' => 'search',
                'js-filter-input' => $id
            ],
            'label' => __('Search', 'modularity')
        ])
        @endfield

        @foreach($rows as $row)
            @collection__item([
                'link' => $row['href'],
                'icon' => $row['icon'],
                'attributeList' => [
                    'js-filter-item' => ''
                ]
            ])

                @typography([
                    'element' => 'span',
                    'attributeList' => [
                        'js-filter-data' => ''
                    ]
                ])
                    {{ $row['title'] }} ({{ $row['type'] }}, {{ $row['filesize'] }})
                @endtypography

            @endcollection__item
        @endforeach
    @endcollection
@endcard
