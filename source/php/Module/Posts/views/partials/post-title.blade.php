@typography([
    'id' => 'mod-posts-' . $ID . '-label',
    'element' => $element ?? 'h2',
    'variant' => $variant ?? 'h4',
    'classList' => $classList ?? []
])
    {!! $postTitle !!}
@endtypography
