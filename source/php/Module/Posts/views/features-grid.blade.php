@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}"
    aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @include('partials.post.box')
        </div>
    @endforeach
</div>

@include('partials.more')
