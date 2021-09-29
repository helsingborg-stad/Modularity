@hero([
    "image" => $modHeroImage->url,
    "imageFocus" => $modHeroImage->focus,
    "size" => $modHeroSize,
    "overlay" => $modHeroOverlayType,
    "color" => $modHeroFontColor,
    "title" => !$hideTitle ? $postTitle : false,
    "byline" => $modHeroByline,
    "paragraph" => $modHeroBody,
    "stretch" => $stretch
])
@endhero