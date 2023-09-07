@segment([
    'layout' => 'card',
    'title' => $post->showTitle ? $post->postTitle : false,
    'context' => ['module.posts.segment'],
    'meta' => $display_reading_time ? $post->readingTime : false,
    'tags' => $post->termsUnlinked,
    'image' => $post->thumbnail['src'],
    'date' => $post->postDate,
    'dateBadge' => $post->dateBadge,
    'buttons' => [['text' => $labels['readMore'], 'href' => $post->permalink, 'color' => 'primary']],
    'containerAware' => true,
    'reverseColumns' => true,
    'classList' => ['c-segment--slider'],
    'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
    'context' => ['module.posts.segment'],
    'attributeList' => array_merge($post->attributeList, []),
    ])
    @slot('floating')
        @if (!empty($post->floating['floating']))
            @icon($post->floating['floating'])
            @endicon
        @endif
    @endslot
@endsegment
