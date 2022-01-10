@hero([
    "classList" => $stretch ? [$class] : [],
    "image" => $modHeroImage->url,
    "imageFocus" => $modHeroImage->focus,
    "size" => $modHeroSize,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $modHeroByline,
    "paragraph" => $modHeroBody,
    "stretch" => $stretch,
    "context" => ['hero', 'module.hero']
])
@endhero