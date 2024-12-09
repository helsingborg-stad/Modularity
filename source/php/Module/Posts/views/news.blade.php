@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

@if (!empty($posts))
    @foreach ($posts as $post)
        @include('partials.post.news-item')
    @endforeach
@endif