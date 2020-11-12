@card(['classList' => ['c-card--panel']])

    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                "element" => "h4"
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @if ($type == 'upload')

            <!-- Mp4 format -->
            @if (isset($video_mp4) && !empty($video_mp4))
                @video([
                    'formats' => [
                        ['src' => $video_mp4['url'] , 'type' => "mp4"],
                    ],
                    attributeList => [
                        'poster' => ($image !== false) ? $image[0] : '',
                        'preload' => 'auto',
                        'autoplay' => true,
                        'loop' => true,
                        'muted' => true
                    ],
                    classList => ['ratio-16-9']
                ])
                @endvideo
            @endif

            <!-- Webm format -->
            @if (isset($fields['video_webm']) && !empty($fields['video_webm']))
                @video([
                    'formats' => [
                        ['src' => $video_webm['url'] , 'type' => "webm"],
                    ],
                    attributeList => [
                        'poster' => ($image !== false) ? $image[0] : '',
                        'preload' => 'auto',
                        'autoplay' => true,
                        'loop' => true,
                        'muted' => true
                    ],
                    classList => ['ratio-16-9']
                ])
                @endvideo
            @endif

            <!-- Ogg format -->
            @if (isset($fields['video_ogg']) && !empty($fields['video_ogg']))
                 @video([
                    'formats' => [
                        ['src' => $video_ogg['url'] , 'type' => "ogg"],
                    ],
                    attributeList => [
                        'poster' => ($image !== false) ? $image[0] : '',
                        'preload' => 'auto',
                        'autoplay' => true,
                        'loop' => true,
                        'muted' => true
                    ],
                    classList => ['ratio-16-9']
                ])
                @endvideo
            @endif

    @else
        {!! apply_filters('the_content', $embed_link) !!}
    @endif
@endcard
