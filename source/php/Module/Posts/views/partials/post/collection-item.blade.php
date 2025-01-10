@collection__item([
    'link' => $post->permalink,
    'classList' => [$posts_columns],
    'context' => ['module.posts.collection__item'],
    'before' => $post->readingTime,
    'containerAware' => true,
    'bordered' => true,
    'attributeList' => array_merge($post->attributeList, []),
])
    @includeWhen(
        !empty($post->callToActionItems['floating']['icon']),
        'partials.floating'
    )

    @slot('before')
        @if ($post->images['thumbnail1:1']['src'] ?? false)
            @image([
                'src' => $post->images['thumbnail1:1']['src'],
                'alt' => $post->images['thumbnail1:1']['alt'],
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
                {{ $post->postTitle }}
            @endtypography
            @if ($post->getIcon())
                @inlineCssWrapper([
                    'styles' => ['background-color' => $post->getIcon()->getCustomColor(), 'display' => 'flex'],
                    'classList' => [
                        $post->getIcon()->getCustomColor() ? '' : 'u-color__bg--primary',
                        'u-rounded--full',
                        'u-detail-shadow-3'
                    ]
                ])
                    @icon([
                        'icon' => $post->getIcon()->getIcon(),
                        'color' => 'white',
                        'backgroundColor' => $post->getIcon()->getCustomColor(),
                    ])
                    @endicon
                @endinlineCssWrapper
            @endif
        @endgroup
        @if($post->termsUnlinked)
            @tags([
                'tags' => $post->termsUnlinked,
                'classList' => ['u-padding__y--2'],
                'format' => false
            ])
            @endtags
        @endif
        @typography([])
            {!! $post->excerptShort !!}
        @endtypography

    @endgroup
@endcollection__item
