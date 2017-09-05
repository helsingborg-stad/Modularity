<div>
    @if (!$hideTitle && !empty($post_title))
        <h2>{!! apply_filters('the_title', $post_title) !!}</h2>
    @endif

    <div class="{{ $classes }} {{ $slider_format }} slider-layout-{{ $slider_layout }}">
        <div data-flickity='{!! $flickity !!}'>
            @foreach ($slides as $slide)
            <div class="slide type-{{ $slide['acf_fc_layout'] }} {{ (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) ? 'has-text-block' : '' }}" {{ $slideColumns > 1 ? 'style="width:' . 100/$slideColumns . '%;"' : '' }} style="position:relative;">

                <!-- Link start -->
                @if (isset($slide['link_type']) && !empty($slide['link_type']) && $slide['link_type'] != 'false')
                    <a href="{{ isset($slide['link_url']) && !empty($slide['link_url']) ? $slide['link_url'] : '#' }}" {{ (isset($slide['link_target']) && $slide['link_target'] === true) ? 'target="_blank"' : '' }}>
                @endif

                <!-- Slides -->
                @include('partials.' . $slide['acf_fc_layout'])

                <!-- Link end -->
                @if (isset($slide['link_type']) && !empty($slide['link_type']) && $slide['link_type'] != 'false')
                </a>
                @endif

            </div>
            @endforeach
        </div>
    </div>
</div>
