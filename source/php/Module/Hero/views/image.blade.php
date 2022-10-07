@hero([
    "classList" => $stretch ? [$class] : [],
    "image" => $image,
    "imageFocus" => $imageFocus,
    "size" => $size,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $byline,
    "paragraph" => $paragraph,
    "stretch" => $stretch,
    "context" => ['hero', 'module.hero', 'module.hero.image']
])
@endhero