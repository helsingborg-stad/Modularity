@if (!$hideTitle && !empty($post_title))
    @typography([
        "variant" => "h4",
        "classList" => ['module-title']
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
