@box([
    'heading' => $post->postTitle,
    'content' => $post->excerptShort,
    'link' => $post->permalink,
    'meta' => $post->termsUnlinked,
    'date' => $post->postDateFormatted,
    'ratio' => $ratio,
    'image' => $post->imageContract ?? $post->image,
])
    @slot('metaArea')
        @includeWhen(!empty($post->readingTime), 'partials.read-time')
        @includeWhen($post->commentCount !== false, 'partials.comment-count')
    @endslot
@endbox
