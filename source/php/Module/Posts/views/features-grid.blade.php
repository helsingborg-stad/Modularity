@includeWhen((!$hideTitle && !empty($postTitle))|| !empty($titleCTA), 'partials.post-title',
    ['titleCTA' => $titleCTA ?? null]
)
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}{{ (!empty($preamble)||(!$hideTitle && !empty($postTitle))) ? ' u-margin__top--4' : '' }}"
    aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @if($posts)
        @foreach ($posts as $post)
            <div class="{{ $posts_columns }}">
                @include('partials.post.box')
            </div>
        @endforeach
    @endif
</div>

@include('partials.more')
