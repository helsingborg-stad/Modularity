@card([
    'link' => $post->permalink,
    'imageFirst' => true,
    'heading' => $post->postTitle,
    'context' => ['module.posts.index'],
    'content' => $post->excerptShort,
    'tags' => $post->termsUnlinked,
    'meta' => $post->readingTime,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'classList' => $display_reading_time ? ['c-card--with-reading-time', 'u-height--100'] : ['u-height--100'],
    'containerAware' => true,
    'hasAction' => true,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'image' => $post->image,
    'postId' => $post->id,
    'postType' => $post->postType,
    'icon' => $post->termIcon,
    'attributeList' => array_merge($post->attributeList, []),
])
    @includeWhen(!empty($post->callToActionItems['floating']), 'partials.floating')
@endcard