@include('partials.post-filters')
<div class="o-grid u-margin__bottom--5">
    <div class="o-grid-12@sm o-grid-8@md o-grid-8@lg">
        @if (!$hideTitle && !empty($postTitle))
            @typography([
                'id' => 'mod-posts-' . $sliderId . '-label',
                'element' => 'h2',
                'variant' => 'h2',
                'classList' => ['module-title', 'u-margin__bottom--0']
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
        @includeWhen($postsDisplayAs != 'segment', 'partials.slider.slider-navigation')
</div>

@slider([
    'id'              => isset($blockData['anchor']) ? $blockData['anchor']: 'mod-posts-' . $sliderId,
    'classList'       => ['c-slider--post'],
    'showStepper'     => false,
    'autoSlide'       => false,
    'isPost'          => true,
    'customButtons'   => $postsDisplayAs != 'segment' ? 'js-custom-buttons-' . $sliderId : false,
    'attributeList' => [
        'aria-labelledby' => 'mod-slider-' . $sliderId . '-label',
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
