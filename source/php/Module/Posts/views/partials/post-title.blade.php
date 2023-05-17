@typography([
    'id' => 'mod-posts-' . $ID . '-label',
    'element' => $element ?? 'h2',
    'variant' => $variant ?? 'h2',
    'classList' => $classList ?? []
])
    {!! $postTitle !!}
@endtypography
