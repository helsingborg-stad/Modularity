@segment([
    'layout' => 'card',
    'title' => $post->showTitle ? $post->post_title : false,
    'context' => ['module.posts.segment'],
    'meta' => $display_reading_time ? $post->reading_time : false,
    'tags' => $post->tags,
    'image' => $post->thumbnail[0],
    'date' => $post->showDate
        ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
        : false,
    'content' => $post->post_content,
    'buttons' => [['text' => $labels['readMore'], 'href' => $post->link]],
    'containerAware' => true,
    'reverseColumns' => true,
    'classList' => ['c-segment--slider'],
    'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
    'context' => ['module.posts.segment']
])
    @slot('floating')
        @includeWhen(!empty($post->floatingIcon), 'partials.icon')
    @endslot
@endsegment
