@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

{!! $renderPosts(Municipio\PostObject\Renderer\RenderType::BoxGridItemCollection) !!}

@include('partials.more')
