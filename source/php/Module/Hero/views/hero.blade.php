@hero([
    "image" => $modHeroImage->url,
    "imageFocus" => $modHeroImage->focus,
    "size" => $modHeroSize,
    "color" => $modHeroFontColor,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $modHeroByline,
    "paragraph" => $modHeroBody,
    "stretch" => $stretch
])
@endhero