<div>
    @if (!$hideTitle && !empty($post_title))
        @typography([
            "variant" => "h2"
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

</div>
