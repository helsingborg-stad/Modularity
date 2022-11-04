@include('partials.post-filters')
<div class="o-grid u-margin__bottom--5">
    <div class="o-grid-12@sm o-grid-8@md o-grid-8@lg">
        @if (!$hideTitle && !empty($postTitle))
            @typography([
                'id' => 'mod-posts-' . $ID . '-label',
                'element' => 'h4',
                'variant' => 'h2',
                'classList' => ['module-title']
            ])
                {!! $postTitle !!}
            @endtypography
        @endif

        @if ($preamble)
            @typography([
                'classList' => ['module-preamble'] 
            ])
                {!! $preamble !!}
            @endtypography
        @endif
    </div>
    <div class="o-grid-12@sm o-grid-4@md o-grid-4@lg u-display--flex u-align-items--end u-justify-content--end">
        @if (($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url))
        <div class="t-read-more-section">
            @button([
                'text' => __('Show more', 'modularity'),
                'color' => 'default',
                'style' => 'basic',
                'href' => $archive_link_url . "?" . http_build_query($filters),
                'classList' => ['u-flex-grow--1@xs', 'u-margin__right--2']
            ])
            @endbutton
        </div>
        @endif
        <div class="splide__arrows c-slider__arrows" id="js-custom-buttons-{{$ID}}">
            @button([
                'classList' => ['splide__arrow', 'splide__arrow--prev'],
                'icon' => 'arrow_back_ios_new'
            ])
            @endbutton
            @button([
                'classList' => ['splide__arrow', 'splide__arrow--next'],
                'icon' => 'arrow_forward_ios'
            ])
            @endbutton
        </div>
    </div>
</div>

@slider([
    'id'              => isset($blockData['anchor']) ? $blockData['anchor']: 'mod-posts-' . $ID,
    'classList'       => ['c-slider--post'],
    'showStepper'     => false,
    'autoSlide'       => false,
    'isPost'          => true,
    'customButtons'   => 'js-custom-buttons-' . $ID,
    'attributeList' => [
        'aria-labelledby' => 'mod-slider-' . $ID . '-label',
        'data-slider-gap' => 48,
        'data-slides-per-page' => $slider->slidesPerPage
    ]
])
    @foreach ($posts as $post)
        @slider__item([
            'classList' => ['c-slider__item--post'],
        ])
            @if ($postsDisplayAs === 'index' || $postsDisplayAs === 'items' || $postsDisplayAs === 'news')
                @include('partials.slider.index')
                @else
                @include('partials.slider.' . $postsDisplayAs)
            @endif

        @endslider__item
    @endforeach
    
@endslider
