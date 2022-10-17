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

@if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
	@button([
		'text' => __('Show all', 'modularity'),
		'color' => 'default',
		'style' => 'basic',
		'href' => $archive_link_url . '?' . http_build_query($filters),
	])
	@endbutton
@endif

@slider([
    'id'              => isset($blockData['anchor']) ? $blockData['anchor']: 'mod-posts-' . $ID,
    'showStepper'     => $slider->showStepper,
    'autoSlide'       => $slider->autoSlide,
    'repeatSlide'     => $slider->repeatSlide,
    'attributeList' => [
        'aria-labelledby' => 'mod-slider-' . $ID . '-label',
        'data-slides-per-page' => $slider->slidesPerPage
    ]
])
    @foreach ($posts as $post)
        @slider__item([
            'title' => $post->post_title,
            'desktop_image' => isset($post->thumbnail[0]) ? $post->thumbnail[0] : false,
            'containerColor' => 'none',
            'overlay' => 'dark',
            'textColor' => 'white'
        ])
        @endslider__item
    @endforeach
@endslider
