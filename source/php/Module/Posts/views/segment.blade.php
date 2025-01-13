@includeWhen((!$hideTitle && !empty($postTitle))|| !empty($userCanEditPosts), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

@if($posts)
    <div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}{{ (!empty($preamble)||(!$hideTitle && !empty($postTitle))) ? ' u-margin__top--4' : '' }}"
        @if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
        @foreach ($posts as $post)
            <div class="{{ $posts_columns }}">
                @include('partials.post.segment')
            </div>
        @endforeach
    </div>

    @include('partials.more')

@endif