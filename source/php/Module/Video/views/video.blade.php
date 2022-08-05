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
                        'loop' => true,
                        'muted' => true
                    ],
                    'classList' => ['ratio-16-9', 'embed__fit--cover']
                ])
                @endvideo
        </div>
    @else
        @if($embedCode)
            <div class="embed embed__ratio--16-9" iframe="mod-video">
                @if($image !== false)
                    <div class="embed__poster" data-embed-id="{{ $id }}">
                        <img src="{{ $image[0] }}" alt="{{ $postTitle }}" class="embed__poster__image" />
                    </div>

                    <script id="{{ $id }}" type="x-video-embed">
                        {!! $embedCode !!}
                    </script>
                    
                @else








  
                    <div style="height: 1000px;">
                        <div class="u-level-top u-position--absolute u-align--middle u-padding__x--3" iframe-acceptance-wrapper style="top:20%;">
                            @typography([
                                'variant' => 'h2',
                                'element' => 'h4',
                            ])
                                Informationen i den här rutan hämtas från en extern leverantör
                            @endtypography
                            @typography([
                                'classList' => ['u-padding__bottom--4']
                            ])
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eget viverra ex, in facilisis ex. Praesent sit amet massa felis. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                            @endtypography
                            @button([
                                'text' => 'Visa informationen',
                                'color' => 'primary',
                                'attributeList' => [
                                    'data-js-toggle-trigger' => 'show-iframe'
                                ],
                            ])
                            @endbutton
                        </div>
                        <div style="filter:blur(20px)">
             {!! $embedCode !!}   
            </div>
                    </div> 





















                @endif
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
                'classList' => ['u-margin--2'],
            ])
            @endnotice
        @endif
    @endif
@endcard
