@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}{{ (!empty($preamble)||(!$hideTitle && !empty($postTitle))) ? ' u-margin__top--4' : '' }}"
    aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    {!! $renderPosts(\Municipio\PostObject\PostObjectRenderer\Appearances\Appearance::BoxGridItem) !!}
</div>

@include('partials.more')
