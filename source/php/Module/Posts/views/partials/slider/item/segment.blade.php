@segment([
    'layout' => 'card',
    'title' => $post->showTitle ? $post->post_title : false,
    'context' => ['module.posts.segment'],
    'meta' => $display_reading_time ? $post->reading_time : false,
    'tags' => $post->tags,
    'image' => $post->thumbnail[0],
    'date' => $post->post_date,
    'dateBadge' => $post->dateBadge,
    'buttons' => [['text' => $labels['readMore'], 'href' => $post->link, 'color' => 'primary']],
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
