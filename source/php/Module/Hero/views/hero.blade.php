@hero([
    "image" => $modHeroImage->url,
    "imageFocus" => $modHeroImage->focus,
    "size" => $modHeroSize,
    "overlay" => $modHeroOverlayType,
    "color" => $modHeroFontColor,
    "title" => apply_filters('the_title', $post_title),
    "byline" => $modHeroByline,
    "paragraph" => $modHeroBody,
    "stretch" => $stretch
])
@endhero