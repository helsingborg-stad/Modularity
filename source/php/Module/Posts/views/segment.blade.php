@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

@if($posts)
    <div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}{{ (!empty($preamble)||(!$hideTitle && !empty($postTitle))) ? ' u-margin__top--4' : '' }}"
        @if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
        {!! $renderPosts(\Municipio\PostObject\PostObjectRenderer\Appearances\Appearance::SegmentGridItem) !!}
    </div>

    @include('partials.more')

@endif