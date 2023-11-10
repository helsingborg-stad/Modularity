@segment([
    'layout' => 'card',
    'title' => !empty($post->showTitle) ? $post->postTitle : false,
    'context' => ['module.posts.segment'],
    'meta' => !empty($display_reading_time) ? $post->readingTime : false,
    'tags' => !empty($post->termsUnlinked) ? $post->termsUnlinked : false,
    'image' => !empty($post->thumbnail['src']) ? $post->thumbnail['src'] : false,
    'content' => !empty($post->showExcerpt) ? $post->excerptShort : false,
    'date' => !empty($post->postDate) ? $post->postDate : false,
    'dateBadge' => !empty($post->dateBadge) ? $post->dateBadge : false,
    'buttons' => [['text' => $labels['readMore'], 'href' => $post->permalink, 'color' => 'primary']],
    'containerAware' => true,
    'reverseColumns' => true,
    'classList' => ['c-segment--slider'],
    'icon' => !empty($post->termIcon['icon']) ? $post->termIcon : false,
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
