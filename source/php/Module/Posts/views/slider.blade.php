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

@if ($preamble)
    @typography([
        'classList' => ['module-preamble', 'u-margin__bottom--3'] 
    ])
        {!! $preamble !!}
    @endtypography
@endif

{{-- Temporarily commented out to display Show more button --}}
{{-- @if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url) --}}
@if ($posts_data_source !== 'input' && $archive_link_url)
    <div class="t-read-more-section u-display--flex u-align-content--center u-margin__y--4">
        @button([
            'text' => __('Show more', 'modularity'),
            'color' => 'secondary',
            'style' => 'filled',
            'href' => $archive_link_url . "?" . http_build_query($filters),
            'classList' => ['u-flex-grow--1@xs']
        ])
        @endbutton
    </div>
@endif

@slider([
    'id'              => isset($blockData['anchor']) ? $blockData['anchor']: 'mod-posts-' . $ID,
    'classList'       => ['c-slider--post'],
    'showStepper'     => false,
    'autoSlide'       => false,
    'isPost'           => true,
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

@if ($posts_data_source !== 'input' && $archive_link)
	@button([
		'text' => __('Show all', 'modularity'),
		'color' => 'default',
		'style' => 'basic',
		'href' => $archive_link_url . '?' . http_build_query($filters),
	])
	@endbutton
@endif
