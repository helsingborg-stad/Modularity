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
                    @if($prepareList)
                        @foreach ($prepareList as $post)
                            @if ($post['link'] && $post['title'])
                                @collection__item([
                                    'displayIcon' => true,
                                    'icon' => 'arrow_forward',
                                    'link' => $post['link']
                                ])
                                    @typography([
                                        'element' => 'h2',
                                        'variant' => 'h4'
                                    ])
                                        {{ $post['title'] }}
                                    @endtypography
                                @endcollection__item
                            @endif
                        @endforeach
                    @endif
                @endcollection
            </div>
        </div>
    @endif
@endcard

@include('partials.more')