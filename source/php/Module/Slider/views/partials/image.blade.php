@if ($slide['image_use'] !== false)
<div class="slider-image slider-image-desktop {{ apply_filters('Modularity/slider/desktop_image_hidden', 'hidden-xs hidden-sm') }}" style="background-image:url({{ ($slide['image_use'] !== false) ? $slide['image_use'][0] : '' }})">
	<!-- Text block -->
	@if (isset($slide['activate_textblock']) && $slide['activate_textblock'])
	@include('partials.textblock')'
	@endif
</div>
@endif

@if ($slide['mobile_image_use'] !== false)
<div class="slider-image slider-image-mobile {{ apply_filters('Modularity/slider/mobile_image_hidden', 'hidden-md hidden-lg') }}" style="background-image:url({{ ($slide['mobile_image_use'] !== false) ? $slide['mobile_image_use'][0] : '' }})">
	<!-- Text block -->
	@if (isset($slide['activate_textblock']) && $slide['activate_textblock'])
	@include('partials.textblock')'
	@endif
</div>
@endif
