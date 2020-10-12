@include('partials.post-filters')

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

    @if (!empty($prepareList))
        @collection([
            'sharpTop' => true
         ])
            @foreach($prepareList as $post)
                @if ($post['href'] && $post['columns'] && $post['columns'][0])
                    @collection__item([
                        'icon' => 'keyboard_arrow_right',
                        'link' => $post['href']
                        ])

                    @typography(['element' => 'h4'])
                        {{$post['columns'][0]}}
                    @endtypography

                    @endcollection__item
                @endif
            @endforeach
        @endcollection
    @endif
@endcard