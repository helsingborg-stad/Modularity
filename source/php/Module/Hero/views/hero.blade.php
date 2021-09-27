@hero([
    "image" => $modHeroImage->url,
    "imageFocus" => $modHeroImage->focus,
    "size" => $modHeroSize,
    "overlay" => $modHeroOverlayType,
    "color" => $modHeroFontColor,
    "title" => !$hideTitle ? apply_filters('the_title', $post_title) : false,
    "byline" => $modHeroByline,
    "paragraph" => $modHeroBody,
    "stretch" => $stretch
])
@endhero