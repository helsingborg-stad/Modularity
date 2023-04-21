@include('partials.post-filters')

@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid {{ $stretch ? 'o-grid--stretch' : '' }} {{ $noGutter ? 'o-grid--no-gutter' : '' }}"
    aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @include('partials.post.box')
        </div>
    @endforeach
</div>
