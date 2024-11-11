<div class="o-grid u-margin__bottom--5">
    <div class="o-grid-12@sm o-grid-8@md o-grid-8@lg o-grid-8@xl">
        @includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title', [
            'ID' => $sliderId,
            'classList' => ['module-title', 'u-margin__bottom--0'],
        ])
        @includeWhen($preamble, 'partials.preamble')
    </div>
    @includeWhen($postsDisplayAs != 'segment', 'partials.slider.slider-navigation')
</div>

@if($posts)

    @slider([
        'id' => isset($blockData['anchor']) ? $blockData['anchor'] : 'mod-posts-' . $sliderId,
        'classList' => ['c-slider--post'],
        'showStepper' => false,
        'autoSlide' => false,
        'isPost' => true,
        'repeatSlide' => $postsDisplayAs != 'segment' ? true : false,
        'customButtons' => $postsDisplayAs != 'segment' ? 'js-custom-buttons-' . $sliderId : false,
        'containerAware' => true,
        'attributeList' => [
            'aria-labelledby' => 'mod-slider-' . $sliderId . '-label',
            'data-slides-per-page' => $slider->slidesPerPage
        ]
    ])
        @foreach ($posts as $key => $post)
            @if ($postsDisplayAs === 'features-grid')
                {!! $renderPosts(\Municipio\PostObject\PostObjectRenderer\Appearances\Appearance::BoxSliderItem) !!}
            @else
                @slider__item([
                    'classList' => ['c-slider__item--post']
                ])
                    @if ($postsDisplayAs === 'index' || $postsDisplayAs === 'items' || $postsDisplayAs === 'news')
                        @include('partials.slider.item.index')
                    @else
                        @include('partials.slider.item.' . $postsDisplayAs, [
                            'display_reading_time' => $display_reading_time,
                        ])
                    @endif
                @endslider__item
            @endif
        @endforeach
    @endslider

    @include('partials.more')

@endif