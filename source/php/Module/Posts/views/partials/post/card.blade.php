@card([
    'link' => $post->permalink,
    'heading' => $post->postTitle,
    'context' => ['module.posts.index'],
    'content' => $post->excerptShort,
    'tags' => $post->termsUnlinked,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'classList' => ['u-height--100'],
    'containerAware' => true,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'image' => $post->image,
    'icon' => $post->termIcon,
    'attributeList' => array_merge($post->attributeList, []),
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