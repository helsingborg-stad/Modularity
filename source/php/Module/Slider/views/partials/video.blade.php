@php $slide = (array)$slide; @endphp

@if ($slide['type'] == 'embed')
    <?php echo \Modularity\Module\Slider\Slider::getEmbed($slide['embed_link'], ['player'], $slide['image_use']); ?>
@else
    <!-- Pause & Play icon -->
    <div class="embeded-mini-toolbar
            @if (isset($slide['slider-show-on-hover']) && !empty( $slide['slider-show-on-hover'])){{ $slide['slider-show-on-hover'] }} @endif
    @if (isset($slide['slider-show-pause-icon']) && !empty($slide['slider-show-pause-icon'])) {{ $slide['slider-show-pause-icon'] }} @endif
    @if (isset($slide['slider-icon-transparacy']) && !empty($slide['slider-icon-transparacy'])) slider-icon-opacity-{{ $slide['slider-icon-transparacy'] }} @endif ">
        <i class="pricon pricon-lg pricon-badge pricon-pause embed-control embeded-pause"></i>
        <i class="pricon pricon-lg pricon-badge pricon-play embed-control embeded-play hidden"></i>
    </div>
    <div class="slider-video" style="background-image:url('{{ ($slide['image_use'] !== false) ? $slide['image_use'][0] : '' }}');">
        <video id="video_<?php echo uniqid(); ?>" poster="{{ ($slide['image_use'] !== false) ? $slide['image_use'][0] : '' }}" preload="auto" autoplay loop muted>

            <!-- Mp4 -->
            @if (isset($slide['video_mp4']) && !empty($slide['video_mp4']))
                <source src="{{ $slide['video_mp4']['url'] }}" type="video/mp4">
            @endif

            <!-- Webm -->
            @if (isset($slide['video_webm']) && !empty($slide['video_webm']))
                <source src="{{ $slide['video_webm']['url'] }}" type="video/webm">
            @endif

            <!-- Ogg -->
            @if (isset($slide['video_ogg']) && !empty($slide['video_ogg']))
                <source src="{{ $slide['video_ogg']['url'] }}" type="video/ogg">
            @endif

        </video>
    </div>

    <!-- Text block -->
    @if (isset($slide['activate_textblock']) && $slide['activate_textblock'])
    @include('partials.textblock')'
    @endif
@endif
