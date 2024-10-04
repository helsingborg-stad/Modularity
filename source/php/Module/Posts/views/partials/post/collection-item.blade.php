@collection__item([
    'link' => $post->permalink,
    'classList' => [$posts_columns],
    'context' => ['module.posts.collection__item'],
    'before' => $post->readingTime,
    'containerAware' => true,
    'bordered' => true,
    'attributeList' => array_merge($post->attributeList, []),
])
    @includeWhen(!empty($post->callToActionItems['floating']), 'partials.floating')
    @slot('before')
        @if ($post->image)
            @image([
                'src' => $post->image['src'],
                'alt' => $post->image['alt'],
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
            @if ($post->termIcon)
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
