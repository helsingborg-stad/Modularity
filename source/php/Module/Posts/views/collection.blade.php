@include('partials.post-filters')

@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')
@collection([
    'classList' => ['c-collection', 'o-grid', 'o-grid--horizontal'],
])
    @foreach ($posts as $post)
        @include('partials.post.collection-item')
    @endforeach
@endcollection
