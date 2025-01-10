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
    'icon' => $post->getIcon() ? [
        'icon' => $post->getIcon()->getIcon(),
        'color' => 'white',
        'backgroundColor' => $post->getIcon()->getCustomColor(),
    ] : null,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'attributeList' => $post->attributeList ?? [],
    'classList' => $classList ?? [],
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