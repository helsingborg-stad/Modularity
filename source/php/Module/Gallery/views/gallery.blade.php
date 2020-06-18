<div>
    @if (!$hideTitle && !empty($post_title))
        @typography([
        "variant" => "h2"
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography

    @endif

    @if ($image)
        @gallery([
            'list' => $image,
            'classList' => [$classes, 'image-gallery']
        ])
        @endgallery
    @endif

</div>
