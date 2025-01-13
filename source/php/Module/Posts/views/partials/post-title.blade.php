@group([
    'direction' => 'horizontal',
    'justifyContent' => 'space-between'
])
    @if (!$hideTitle && !empty($postTitle))
        @typography([
            'id' => 'mod-posts-' . $ID . '-label',
            'element' => $element ?? 'h2',
            'variant' => $variant ?? 'h2',
            'classList' => $classList ?? ['module-title']
        ])
            {!! $postTitle !!}
        @endtypography
    @endif
    @if (!empty($userCanEditPosts))
        hello
    @endif
@endgroup