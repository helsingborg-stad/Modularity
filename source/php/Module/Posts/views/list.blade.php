@card([
    'heading' => false,
    'attributeList' => [
        'aria-labelledby' => 'mod-posts-' . $ID . '-label'
    ],
    'context' => 'module.posts.list'
])
@if (!$hideTitle && !empty($postTitle))
<div class="c-card__header">
    @include('partials.post-title', ['variant' => 'h4', 'classList' => []])
</div>
@endif

    @if (!empty($prepareList))
        <div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}">
            <div class="o-grid-12">
                @collection([
                    'sharpTop' => true,
                    'bordered' => true
                ])
                    {!! $renderPosts(\Municipio\PostObject\PostObjectRenderer\Appearances\Appearance::ListItem) !!}
                @endcollection
            </div>
        </div>
    @endif
@endcard

@include('partials.more')