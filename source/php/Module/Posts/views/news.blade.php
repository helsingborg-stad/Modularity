@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}{{ (!empty($preamble)||(!$hideTitle && !empty($postTitle))) ? ' u-margin__top--4' : '' }}"
@if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
    @if (!empty($posts))
        @foreach ($posts as $post)
            @include('partials.post.news-item', [
                'posts_columns' => $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns,
                'standing' => $loop->first && $highlight_first_column ? true : false
            ])
        @endforeach
    @endif
</div>