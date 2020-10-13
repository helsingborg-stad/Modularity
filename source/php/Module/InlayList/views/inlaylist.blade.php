@card([
    'heading' => apply_filters('the_title', $post_title),
    'classList' => [$classes]
])
    @if (!$hideTitle && !empty($post_title))
        @typography([
            'element'   => 'h4',
            'variant'   => 'h4',
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif

    @if (!empty($items))
        @collection([
            'sharpTop' => true
        ])
        @foreach($items as $item)
            @collection__item([
                'icon' => 'arrow_forward',
                'link' => $post['href']
            ])
                @typography(['element' => 'h4'])
                    {{$item['label']}}
                @endtypography
            @endcollection__item
        @endforeach
        @endcollection
    @endif
@endcard