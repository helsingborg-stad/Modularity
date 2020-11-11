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
        'sharpTop' => true
    ])

        @foreach($rows as $row)
            @collection__item([
                'link' => $row['href'],
                'icon' => $row['icon']
            ])

                @typography(['element' => 'span'])
                    {{ $row['title'] }} ({{ $row['type'] }}, {{ $row['filesize'] }})
                @endtypography

            @endcollection__item
        @endforeach
    @endcollection
@endcard
