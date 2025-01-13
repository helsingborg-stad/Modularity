@newsItem([
    'heading'             => $post->postTitle,
    'content'             => $post->excerpt,
    'image'               => $post->image,
    'date'                => $post->postDateFormatted,
    'readTime'            => $post->readingTime,
    'link'                => $post->permalink,
    'context'             => ['module.posts.news-item'],
    'hasPlaceholderImage' => $post->hasPlaceholderImage,
    'classList' => array_merge($post->classList ?? [], [$posts_columns]),
    'standing' => $standing,
    'attributeList' => array_merge(
        $post->attributeList ?? [], 
        [
            'data-js-post-id' => $post->id
        ]
    ),
])
    @slot('headerLeftArea')
        @if (!empty($postsSources) && count($postsSources) > 1 && !empty($post->originalSite))
            @typography([
                'element' => 'span',
                'variant' => 'bold',
                'classList' => ['u-margin__y--0', 'u-padding__right--1'],
            ])
                {{ $post->originalSite }}
            @endtypography
        @endif
        @if($post->termsUnlinked)
            @tags([
                'compress' => 4, 
                'tags' => $post->termsUnlinked, 
                'format' => false,
            ])
            @endtags
        @endif
    @endslot

    @slot('headerRightArea')
        @includeWhen($post->commentCount !== false, 'partials.comment-count')
    @endslot
@endnewsItem
