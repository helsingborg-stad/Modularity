@include('partials.post-filters')

@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

@collection([
    'classList' => ['c-collection--posts', 'o-grid']
])
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @collection__item([
                'link' => $post->link,
                'classList' => ['c-collection__item--post'],
                'context' => ['module.posts.collection__item'],
                'containerAware' => true
            ])
                @slot('floating')
                    @include('partials.icon')
                @endslot
                @slot('before')
                    @if ($post->showImage && isset($post->thumbnail[0]))
                        @image([
                            'src' => $post->thumbnail[0],
                            'alt' => $post->post_title,
                            'classList' => ['u-width--100']
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
                        @if ($post->termIcon)
                            <div class="c-collection__item__icon">
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
                            </div>
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
        </div>
    @endforeach
@endcollection
