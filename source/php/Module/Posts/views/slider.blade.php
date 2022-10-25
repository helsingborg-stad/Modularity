@include('partials.post-filters')

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

@if ($postColumns)
<h1> post columns: {{ $postColumns }} </h1>
@endif 

@slider([
    'id'              => isset($blockData['anchor']) ? $blockData['anchor']: 'mod-posts-' . $ID,
    'classList'       => ['c-slider--post'],
    'showStepper'     => false,
    'autoSlide'       => false,
    'attributeList' => [
        'aria-labelledby' => 'mod-slider-' . $ID . '-label',
        'data-slider-gap' => 0,
        'data-slides-per-page' => $slider->slidesPerPage
    ]
])
    @foreach ($posts as $post)
        @slider__item([
            'classList' => ['c-slider__item--post'],
        ])
            @if ($postsDisplayAs)
                @include('partials.slider.'. $postsDisplayAs )
            @endif

        @endslider__item
    @endforeach
    
@endslider

@if ($posts_data_source !== 'input' && $archive_link)
	@button([
		'text' => __('Show all', 'modularity'),
		'color' => 'default',
		'style' => 'basic',
		'href' => $archive_link_url . '?' . http_build_query($filters),
	])
	@endbutton
@endif
