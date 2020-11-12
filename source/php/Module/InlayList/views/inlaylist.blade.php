@card([
    'classList' => ['c-card--panel']
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element'   => 'h4',
                'classList' => []
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @if (!empty($items))
        @collection([
            'sharpTop' => true
        ])
        @foreach($items as $item)
            @collection__item([
                'icon' => 'arrow_forward',
                'link' => $item['href']
            ])
                @typography(['element' => 'h4'])
                    {{$item['label']}}
                @endtypography
            @endcollection__item
        @endforeach
        @endcollection
    @endif
@endcard