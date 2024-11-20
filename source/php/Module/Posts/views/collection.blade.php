@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

{!! $renderPosts(Municipio\PostObject\Renderer\RenderType::CollectionItemCollection) !!}

@include('partials.more')
