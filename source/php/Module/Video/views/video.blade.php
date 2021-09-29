@card([
    'attributeList' => [
        'aria-labelledby' => 'mod-video-' . $ID . '-label'
    ],
    'context' => 'module.video'
])

    @if (!$hideTitle && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                "id"        => "mod-video-" . $ID . "-label",
                "element"   => "h4"
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif

    @if ($type == 'upload')

        <div class="embed embed__ratio--16-9">
                @video([
                    'formats' => [
                        ['src' => $source, 'type' => "mp4"],
                    ],
                    'width' => 1080,
                    'height' => 720,
                    attributeList => [
                        'poster' => ($image !== false) ? $image[0] : '',
                        'preload' => 'auto',
                        'autoplay' => true,
                        'loop' => true,
                        'muted' => true
                    ],
                    classList => ['ratio-16-9', 'embed__fit--cover']
                ])
                @endvideo
        </div>
    @else
        
        <div class="embed embed__ratio--16-9">
            {!! wp_oembed_get( $embed_link, array( 'width' => 1080, 'height' => 720 )) !!}
        </div>
        
    @endif
@endcard
