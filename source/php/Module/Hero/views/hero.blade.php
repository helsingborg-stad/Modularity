<div class="{!! $class !!}">
    @hero([
        "image" => $modHeroImage->url,
        "imageFocus" => $modHeroImage->focus,
        "size" => $modHeroSize,
        "title" => !$hideTitle ? $postTitle : false,
        "byline" => $modHeroByline,
        "paragraph" => $modHeroBody,
        "stretch" => $stretch
    ])
    @endhero
</div>