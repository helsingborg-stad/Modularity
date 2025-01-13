@segment([
    'layout' => 'card',
    'title' => $post->postTitle,
    'context' => ['module.posts.segment'],
    'tags' => $post->termsUnlinked,
    'image' => $post->image,
    'date' => $post->postDateFormatted,
    'content' => $post->excerptShort,
    'buttons' => [['text' => $lang['readMore'], 'href' => $post->permalink, 'color' => 'primary']],
    'containerAware' => true,
    'reverseColumns' => $imagePosition,
    'icon' => $post->termIcon,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'classList' => array_merge($post->classList ?? [], $classList ?? []),
    'attributeList' => array_merge(
        $post->attributeList ?? [], 
        [
            'data-js-post-id' => $post->id
        ]
    ),
])
    @includeWhen(
        !empty($post->callToActionItems['floating']['icon']),
        'partials.floating'
    )

    @slot('aboveContent')
        @includeWhen(!empty($post->readingTime), 'partials.read-time')
        @includeWhen($post->commentCount !== false, 'partials.comment-count')
    @endslot
@endsegment