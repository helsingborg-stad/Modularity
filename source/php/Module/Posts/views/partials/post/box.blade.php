@box([
    'heading' => $post->showTitle ? $post->postTitle : false,
    'content' => $post->showExcerpt ? $post->excerptShort : false,
    'link' => $post->permalink,
    'meta' => $post->termsUnlinked,
    'secondaryMeta' => $display_reading_time ? $post->readingTime : false,
    'date' => $post->postDateFormatted,
    'ratio' => $ratio,
    'image' => $post->showImage
        ? [
            'src' => $post->thumbnail['src'] ?? false,
            'alt' => $post->thumbnail['alt'] 
        ]
        : [],
])
@endbox
