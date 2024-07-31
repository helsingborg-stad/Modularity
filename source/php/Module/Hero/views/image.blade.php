@hero([
    "classList" => $stretch ? [$class] : [],
    "image" => $image,
    "imageFocus" => $imageFocus,
    "size" => $size,
    "title" => !$hideTitle && !empty($postTitle) ? $postTitle : false,
    "byline" => $byline,
    "meta" => $meta,
    "paragraph" => $paragraph,
    "stretch" => isset($blockData) ? ((bool) $blockData['align'] == 'full') : $stretch,
    "context" => ['hero', 'module.hero', 'module.hero.image', $sidebarContext . '.animation-item'],
    "ariaLabel" => $ariaLabel,
    "heroView" => $heroView,
    "buttonArgs" => $buttonArgs
])
@endhero