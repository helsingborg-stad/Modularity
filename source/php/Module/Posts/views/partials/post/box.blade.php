@box([
    'heading' => $post->postTitle,
    'content' => $post->excerptShort,
    'link' => $post->permalink,
    'meta' => $post->termsUnlinked,
    'date' => $post->postDateFormatted,
    'ratio' => $ratio,
    'image' => $post->imageContract ?? $post->image,
    'classList' => $post->classList ?? [],
    'attributeList' => array_merge(
        $post->attributeList ?? [], 
        [
            'data-js-item-id' => $post->id
        ]
    ),
])
    @slot('metaArea')
        @includeWhen(!empty($post->readingTime), 'partials.read-time')
        @includeWhen($post->commentCount !== false, 'partials.comment-count')
    @endslot
@endbox
