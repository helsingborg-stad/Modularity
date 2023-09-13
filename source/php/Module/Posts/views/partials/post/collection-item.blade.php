@collection__item([
    'link' => !empty($post->permalink) ? $post->permalink : false,
    'classList' => [$posts_columns],
    'context' => ['module.posts.collection__item'],
    'before' => !empty($display_reading_time) ? $post->readingTime : false,
    'containerAware' => true,
    'bordered' => true,
    'attributeList' => array_merge($post->attributeList, [
    ]),
])
    @if (!empty($post->floating['floating']))
        @slot('floating')
            @icon($post->floating['floating'])
            @endicon
        @endslot
    @endif
    @slot('before')
        @if (!empty($post->showImage) && isset($post->thumbnail['src']))
            @image([
                'src' => $post->thumbnail['src'],
                'alt' => $post->thumbnail['alt'],
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
                {{ !empty($post->showTitle) ? $post->postTitle : false }}
            @endtypography
            @if (!empty($post->termIcon['icon']))
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
        @if(!empty($post->termsUnlinked))
            @tags([
                'tags' => $post->termsUnlinked,
                'classList' => ['u-padding__y--2'],
                'format' => false
            ])
            @endtags
        @endif
        @typography([])
            {!! $post->showExcerpt ? $post->excerptShort : false !!}
        @endtypography

    @endgroup
@endcollection__item
