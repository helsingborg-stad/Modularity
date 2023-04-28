@include('partials.post-filters')

@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')
@collection([
    'classList' => ['c-collection--posts', 'o-grid']
])
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @include('partials.post.collection-item')
        </div>
    @endforeach
@endcollection
