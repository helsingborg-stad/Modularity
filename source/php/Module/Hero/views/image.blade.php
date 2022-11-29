@hero([
    "classList" => $stretch ? [$class] : [],
    "image" => $image,
    "imageFocus" => $imageFocus,
    "size" => $size,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $byline,
    "paragraph" => $paragraph,
    "stretch" => isset($blockData) ? ((bool) $blockData['align'] == 'full') : $stretch,
    "context" => ['hero', 'module.hero', 'module.hero.image', $sidebarContext . '.animation-item'],
    "sidebar" => $sidebarContext ? $sidebarContext : false,
])
@endhero
