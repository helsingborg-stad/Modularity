@hero([
    "classList" => $stretch ? [$class] : [],
    "video" => $video,
    "size" => $size,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $byline,
    "paragraph" => $paragraph,
    "stretch" => isset($blockData) ? ((bool) $blockData['align'] == 'full') : $stretch,
    "context" => ['hero', 'module.hero', 'module.hero.video'],
    "ariaLabel" => $ariaLabel,
    "buttonArgs" => $buttonArgs
])
@endhero
