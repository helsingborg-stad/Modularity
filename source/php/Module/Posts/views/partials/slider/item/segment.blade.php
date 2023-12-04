@segment([
    'layout' => 'card',
    'title' => $post->postTitle,
    'context' => ['module.posts.segment'],
    'meta' => $post->readingTime,
    'tags' => $post->termsUnlinked,
    'image' => !empty($post->image['src']) ? $post->image['src'] : false,
    'content' => $post->excerptShort,
    'date' => $post->postDateFormatted,
    'dateBadge' => $post->dateBadge,
    'buttons' => [['text' => $lang['readMore'], 'href' => $post->permalink, 'color' => 'primary']],
    'containerAware' => true,
    'reverseColumns' => true,
    'classList' => ['c-segment--slider'],
    'icon' => $post->termIcon,
    'attributeList' => array_merge($post->attributeList, []),
    'hasPlaceholder' => !empty($post->hasPlaceholderImage),
    ])
    @slot('floating')
        @if (!empty($post->callToActionItems['floating']))
            @icon($post->callToActionItems['floating'])
            @endicon
        @endif
    @endslot
@endsegment
