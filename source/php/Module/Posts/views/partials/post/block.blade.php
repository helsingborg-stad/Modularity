@block([
    'heading' => $post->postTitle,
    'content' => $post->excerptShort,
    'ratio' => $ratio,
    'meta' => $post->termsUnlinked,
    'secondaryMeta' => $post->readingTime,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'filled' => true,
    'image' => $post->image,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'classList' => ['t-posts-block', ' u-height--100'],
    'context' => ['module.posts.block'],
    'link' => $post->permalink,
    'postId' => $post->id,
    'postType' => $post->postType ?? '',
    'icon' => $post->termIcon,
    'attributeList' => array_merge($post->attributeList, []),
])
    @includeWhen(!empty($post->callToActionItems['floating']), 'partials.floating')
@endblock