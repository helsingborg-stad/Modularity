@block([
    'heading' => $post->postTitle,
    'content' => $post->excerptShort,
    'ratio' => $ratio,
    'meta' => $post->termsUnlinked,
    'secondaryMeta' => $post->readingTime,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'image' => $post->image,
    'classList' => ['t-posts-block', ' u-height--100'],
    'context' => ['module.posts.block'],
    'link' => $post->permalink,
        'icon' => $post->getTermIcon() ? [
        'icon' => $post->getTermIcon()->getIcon(),
        'color' => 'white',
        'backgroundColor' => $post->getTermIcon()->getColor(),
    ] : null,
    'attributeList' => array_merge($post->attributeList, []),
])
    @includeWhen(
        !empty($post->callToActionItems['floating']['icon']), 
        'partials.floating'
    )
    @slot('metaArea')
        @includeWhen($post->commentCount !== false, 'partials.comment-count')
    @endslot
@endblock