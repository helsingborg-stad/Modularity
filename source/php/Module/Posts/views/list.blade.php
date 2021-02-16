@include('partials.post-filters')

@card([
    'heading'       => apply_filters('the_title', $post_title),
    'classList'     => [$classes],
    'attributeList' => [
        'aria-labelledby' => 'mod-posts-' . $ID . '-label'
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'id'        => 'mod-posts-' . $ID . '-label',
                'element'   => "h4"
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @if (!empty($prepareList))
        @collection([
            'sharpTop' => true,
            'bordered' => true
         ])
            @foreach($prepareList as $post)
                @if ($post['href'] && $post['columns'] && $post['columns'][0])
                    @collection__item([
                        'icon' => 'arrow_forward',
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