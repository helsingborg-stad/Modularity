@box([
    'heading' => $post->showTitle ? $post->postTitle : false,
    'content' => $post->showExcerpt ? $post->excerptShort : false,
    'link' => $post->permalink,
    'meta' => $post->termsUnlinked,
    'secondaryMeta' => $display_reading_time ? $post->readingTime : false,
    'date' => $post->postDateFormatted,
    'ratio' => $ratio,
    'image' => $post->showImage && !empty($post->images['thumbnail16:9']['src'])
        ? [
            'src' => $post->images['thumbnail16:9']['src'] ?? false,
            'alt' => $post->images['thumbnail16:9']['alt'] 
        ]
        : [],
])
@endbox
