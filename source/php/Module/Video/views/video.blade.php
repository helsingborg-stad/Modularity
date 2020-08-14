<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    @if ($type == 'upload')

            <!-- Mp4 -->
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

            <!-- Webm -->
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

            <!-- Ogg -->
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
        <?php echo \Modularity\Module\Slider\Slider::getEmbed($embed_link, ['player', 'ratio-16-9'], $image); ?>
    @endif
</div>
