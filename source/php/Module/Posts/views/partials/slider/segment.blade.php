@segment([
    'layout' => 'card',
    'title' => ($post->showTitle ? $post->post_title : false),
    'meta' => '5 mins read',
    'tags' => $post->tags,
    'image' => $post->thumbnail[0],
    'date' => $post->showDate ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date)) : false,
    'content' => $post->post_content,
    'buttons' => [['text' => $labels['readMore'], 'href' => $post->link]],
    'containerAware' => true,
    'reverseColumns' => true
])
@endsegment