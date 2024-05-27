@image([
    'src'           => $image['src'] ?? false,
    'alt'           => $image['alt'] ?? "",
    'caption'       => $image['caption'] ?? "",
    'byline'        => $image['byline'] ?? "",
    'context'       => ['module.image', $sidebarContext . '.module.image', $sidebarContext . '.animation-item'],
])
@endimage