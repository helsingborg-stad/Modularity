@hero([
    "classList" => $stretch ? [$class] : [],
    "video" => $video,
    "size" => $size,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $byline,
    "paragraph" => $paragraph,
    "stretch" => $stretch,
    "context" => ['hero', 'module.hero', 'module.hero.video']
])
@endhero
