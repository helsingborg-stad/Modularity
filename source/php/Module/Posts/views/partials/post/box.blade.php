@box([
    'heading' => $post->showTitle ? $post->post_title : false,
    'content' => $post->showExcerpt ? $post->post_content : false,
    'link' => $post->link,
    'meta' => $post->tags,
    'secondaryMeta' => $display_reading_time ? $post->reading_time : false,
    'date' => $post->showDate
        ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
        : false,
    'ratio' => $ratio,
    'image' => $post->showImage
        ? [
            'src' => $post->thumbnail[0] ?? false,
            'alt' => $post->post_title
        ]
        : [],
    'icon' => [
        'name' => $post->item_icon ?? false
    ]
])
@endbox
