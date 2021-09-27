@if (!$hideTitle && !empty($postTitle))
    @typography([
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

@if ($images)
    @gallery([
        'list' => $images,
        'classList' => [$classes, 'image-gallery']
    ])
    @endgallery
@endif
