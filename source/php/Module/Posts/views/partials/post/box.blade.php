@box([
    'heading' => $post->postTitle,
    'content' => $post->excerptShort,
    'link' => $post->permalink,
    'meta' => $post->termsUnlinked,
    'date'                => [
        'timestamp' => $post->getArchiveDateTimestamp(),
        'format'    => $post->getArchiveDateFormat(),
    ],
    'dateBadge' => $post->getArchiveDateFormat() == 'date-badge',
    'ratio' => $ratio,
    'image' => $post->imageContract ?? $post->image,
])
    @slot('metaArea')
        @includeWhen(!empty($post->readingTime), 'partials.read-time')
        @includeWhen($post->commentCount !== false, 'partials.comment-count')
    @endslot
@endbox
