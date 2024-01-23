@box([
    'heading' => $post->postTitle,
    'content' => $post->excerptShort,
    'link' => $post->permalink,
    'meta' => $post->termsUnlinked,
    'secondaryMeta' => $post->readingTime,
    'date' => $post->postDateFormatted,
    'ratio' => $ratio,
    'image' => $post->image,
])
@endbox
