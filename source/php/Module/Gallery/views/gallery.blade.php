@if (!$hideTitle && !empty($postTitle))
    @typography([
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif

@if ($images)
    @gallery([
        'list' => $images,
        'classList' => [$classes, 'image-gallery'],
        'ariaLabels' => $ariaLabels,
    ])
    @endgallery
@endif
