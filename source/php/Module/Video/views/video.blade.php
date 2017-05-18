<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    @if ($type == 'upload')
        <video class="ratio-16-9" poster="{{ ($image !== false) ? $image[0] : '' }}" preload="auto" autoplay loop muted>
            <!-- Mp4 -->
            @if (isset($video_mp4) && !empty($video_mp4))
                <source src="{{ $video_mp4['url'] }}" type="video/mp4">
            @endif

            <!-- Webm -->
            @if (isset($fields['video_webm']) && !empty($fields['video_webm']))
                <source src="{{ $video_webm['url'] }}" type="video/webm">
            @endif

            <!-- Ogg -->
            @if (isset($fields['video_ogg']) && !empty($fields['video_ogg']))
                <source src="{{ $video_ogg['url'] }}" type="video/ogg">
            @endif
        </video>
    @else
        <?php echo \Modularity\Module\Slider\Slider::getEmbed($embed_link, ['player', 'ratio-16-9'], $image); ?>
    @endif
</div>
