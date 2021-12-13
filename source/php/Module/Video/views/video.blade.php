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
                    'attributeList' => [
                        'poster' => ($image !== false) ? $image[0] : '',
                        'preload' => 'auto',
                        'autoplay' => true,
                        'loop' => true,
                        'muted' => true
                    ],
                    'classList' => ['ratio-16-9', 'embed__fit--cover']
                ])
                @endvideo
        </div>
    @else
        @if($embedCode)
            <div class="embed embed__ratio--16-9">
                {!! $embedCode !!}
            </div>
        @else
            @notice([
                'type' => 'info',
                'message' => [
                    'text' => sprintf($lang->embedFailed, $embed_link),
                ],
                'icon' => [
                    'name' => 'report',
                    'size' => 'md',
                    'color' => 'white'
                ],
                'classList' => !$hasTitle ? ['u-margin__x--2', 'u-margin__bottom--2'] : ['u-margin--2'],
            ])
            @endnotice
        @endif
    @endif
@endcard
