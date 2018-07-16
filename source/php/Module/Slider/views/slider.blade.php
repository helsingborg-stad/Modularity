<div>
    @if (!$hideTitle && !empty($post_title))
        <h2>{!! apply_filters('the_title', $post_title) !!}</h2>
    @endif


    @if (isset($dataBleed) && !empty($dataBleed))
    <style>

        .slider .slide {
            width: {{ $slideWidthMobile }}% !important;
        }

        @media only screen and (min-width: 900px) {
            .slider .slide {
                position: relative;
                width: {{ $slideWidth }}% !important;
            }
        }

    </style>
    @endif

    <div class="{{ $classes }}  @if (!$dataBleed) {{ $slider_format }} @endif slider-layout-{{ $slider_layout }}">
        <div data-flickity='{!! $flickity !!}'>
            @foreach ($slides as $slide)

                <div class="slide type-{{ $slide['acf_fc_layout'] }} {{ (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) ? 'has-text-block' : '' }}">

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
