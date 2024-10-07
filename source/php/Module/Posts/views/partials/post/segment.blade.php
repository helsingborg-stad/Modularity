@segment([
    'layout' => 'card',
    'title' => $post->postTitle,
    'context' => ['module.posts.segment'],
    'meta' => $post->readingTime,
    'tags' => $post->termsUnlinked,
    'image' => $post->image,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'content' => $post->excerptShort,
    'buttons' => [['text' => $lang['readMore'], 'href' => $post->permalink, 'color' => 'primary']],
    'containerAware' => true,
    'reverseColumns' => $imagePosition,
    'icon' => $post->termIcon,
    'hasPlaceholder' => $post->hasPlaceholderImage,
    'attributeList' => $post->attributeList ?? [],
    'classList' => $classList ?? [],
])
@includeWhen(!empty($post->callToActionItems['floating']), 'partials.floating')
@endsegment