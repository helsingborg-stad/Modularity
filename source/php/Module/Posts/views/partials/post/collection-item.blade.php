@collection__item([
    'link' => $post->permalink,
    'classList' => $post->classList ?? [],
    'context' => ['module.posts.collection__item'],
    'before' => $post->readingTime,
    'containerAware' => true,
    'bordered' => true,
    'attributeList' => $post->attributeList ?? []
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
                @element([
                        'attributeList' => [
                            'style' => 'background-color: ' . $post->getIcon()->getCustomColor() ?? 'transparent' . ';',
                        ],
                        'classList' => [
                            'u-display--flex',
                            $post->getIcon()->getCustomColor() ? 'u-padding__x--1' : '',
                            $post->getIcon()->getCustomColor() ? 'u-padding__y--1' : '',
                            'u-rounded--full',
                            'u-detail-shadow-3'
                        ]
                    ])
                        @icon([
                            'icon' => $post->getIcon()->getIcon(),
                            'color' => 'white',
                            'size' => 'md'
                        ])
                        @endicon
                    @endelement
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