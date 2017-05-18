<span class="text-block text-block-left">
    <span>
        <!-- Title -->
        @if (isset($slide['textblock_title']) && !empty($slide['textblock_title']))
            <em class="title block-level h1">{{ $slide['textblock_title'] }}</em>
        @endif

        <!-- Content -->
        @if (isset($slide['textblock_content']) && !empty($slide['textblock_content']))
        {{ $slide['textblock_content'] }}
        @endif
    </span>
</span>

<div class="slider-image slider-image-desktop hidden-xs hidden-sm" style="background-image:url({{ ($slide['image_use'] !== false) ? $slide['image_use'][0] : '' }})"></div>
<div class="slider-image slider-image-mobile hidden-md hidden-lg" style="background-image:url({{ ($slide['mobile_image_use'] !== false) ? $slide['mobile_image_use'][0] : '' }})"></div>

