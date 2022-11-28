@include('partials.post-filters')
<div class="o-grid u-margin__bottom--5">
    <div class="o-grid-12@sm o-grid-8@md o-grid-8@lg">
        @if (!$hideTitle && !empty($postTitle))
            @typography([
                'id' => 'mod-posts-' . $ID . '-label',
                'element' => 'h2',
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
        @include('partials.slider.slider-navigation')
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
