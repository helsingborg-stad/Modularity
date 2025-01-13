@card([
    'link' => $post->permalink,
    'heading' => $post->postTitle,
    'context' => ['module.posts.index'],
    'content' => $post->excerptShort,
    'tags' => $post->termsUnlinked,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'classList' => array_merge($post->classList ?? [], ['u-height--100']),
    'containerAware' => true,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'image' => $post->image,
    'icon' => $post->termIcon,
    'attributeList' => array_merge(
        $post->attributeList ?? [], 
        [
            'data-js-post-id' => $post->id
        ]
    ),
])
    @slot('aboveContent')
        @includeWhen(!empty($post->readingTime), 'partials.read-time')
        @includeWhen($post->commentCount !== false, 'partials.comment-count')
    @endslot

    @includeWhen(
        !empty($post->callToActionItems['floating']['icon']), 
        'partials.floating'
    )
@endcard