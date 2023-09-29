@typography([
    'id' => 'mod-posts-' . $ID . '-label',
    'element' => $element ?? 'h2',
    'variant' => $variant ?? 'h2',
    'classList' => $classList ?? ['module-title']
])
    {!! $post_title !!}
@endtypography