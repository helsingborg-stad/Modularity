@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')
@collection([
    'classList' => ['c-collection', 'o-grid', 'o-grid--horizontal'],
])
    {!! $renderPosts(\Municipio\PostObject\PostObjectRenderer\Appearances\Appearance::CollectionItem) !!}
@endcollection

@include('partials.more')
