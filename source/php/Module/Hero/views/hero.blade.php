@hero([
    "classList" => $stretch ? [$class] : [],
    "image" => $modHeroBackgroundType === 'image' ? $modHeroImage->url : false,
    "imageFocus" => $modHeroImage->focus,
    "video" => $modHeroBackgroundType === 'video' ? $modHeroVideo->url : false,
    "size" => $modHeroSize,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $modHeroByline,
    "paragraph" => $modHeroBody,
    "stretch" => $stretch,
    "context" => ['hero', 'module.hero']
])
@endhero