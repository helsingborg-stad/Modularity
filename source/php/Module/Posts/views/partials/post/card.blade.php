@card([
    'link' => $post->permalink,
    'heading' => $post->postTitle,
    'context' => ['module.posts.index'],
    'content' => $post->excerptShort,
    'tags' => $post->termsUnlinked,
    'meta' => $post->readingTime,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'classList' => ['u-height--100'],
    'containerAware' => true,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'image' => $post->image,
    'icon' => $post->termIcon,
    'attributeList' => array_merge($post->attributeList, []),
])
    @includeWhen(!empty($post->callToActionItems['floating']), 'partials.floating')
@endcard