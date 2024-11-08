@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')
@collection([
    'classList' => ['c-collection', 'o-grid', 'o-grid--horizontal'],
])
    @if($renderedPosts)
        {!! $renderedPosts !!}
    @endif
@endcollection

@include('partials.more')
