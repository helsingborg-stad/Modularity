@collection__item([
    'link' => $post->link,
    'classList' => [$posts_columns],
    'context' => ['module.posts.collection__item'],
    'before' => $display_reading_time ? $post->reading_time : false,
    'containerAware' => true,
    'bordered' => true,
])
    @if (!empty($post->floating['floating']))
        @slot('floating')
            @icon($post->floating['floating'])
            @endicon
        @endslot
    @endif
    @slot('before')
        @if ($post->showImage && isset($post->thumbnail[0]))
            @image([
                'src' => $post->thumbnail[0],
                'alt' => $post->post_title,
            ])
            @endimage
        @endif
    @endslot
    @group([
        'direction' => 'vertical'
    ])
        @group([
            'justifyContent' => 'space-between'
        ])
            @typography([
                'element' => 'h2',
                'variant' => 'h3'
            ])
                {{ $post->showTitle ? $post->post_title : false }}
            @endtypography
            @if ($post->termIcon['icon'])
                @inlineCssWrapper([
                    'styles' => ['background-color' => $post->termIcon['backgroundColor'], 'display' => 'flex'],
                    'classList' => [
                        $post->termIcon['backgroundColor'] ? '' : 'u-color__bg--primary',
                        'u-rounded--full',
                        'u-detail-shadow-3'
                    ]
                ])
                    @icon($post->termIcon)
                    @endicon
                @endinlineCssWrapper
            @endif
        @endgroup
        @tags([
            'tags' => $post->tags,
            'classList' => ['u-padding__y--2'],
            'format' => false
        ])
        @endtags
        @typography([])
            {!! $post->showExcerpt ? $post->post_content : false !!}
        @endtypography

    @endgroup
@endcollection__item
