@card([
    'link' => $post->permalink,
    'heading' => $post->postTitle,
    'context' => ['module.posts.index'],
    'content' => $post->excerptShort,
    'tags' => $post->termsUnlinked,
    'date' => [
        'timestamp' => $post->getArchiveDateTimestamp(),
        'format'    => $post->getArchiveDateFormat(),
    ],
    'dateBadge' => $post->dateBadge ?: $post->getArchiveDateFormat() == 'date-badge',
    'classList' => ['u-height--100'],
    'containerAware' => true,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'image' => $post->image,
    'icon' => $post->getIcon() ? [
        'icon' => $post->getIcon()->getIcon(),
        'color' => 'white',
    ] : null,
    'iconBackgroundColor' => $post->getIcon() ? $post->getIcon()->getCustomColor() : null,
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