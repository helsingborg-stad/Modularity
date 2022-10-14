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

@slider([
	'id'          => $blockData['anchor'],
	'autoSlide'   => $autoSlide ?? false,
	'ratio'       => $ratio ?? '16:9',
	'repeatSlide' => $repeatSlide ?? true,
	'shadow'      => $shadow ?? false,
	'showStepper' => $showStepper ?? true,
	'attributeList' => [
		'aria-labelledby' => 'mod-slider-' . $ID . '-label',
		'data-slides-per-page' => $slides_per_page
	]
])
	@foreach ($posts as $post)
		@includeFirst(['partials.slider-item', $post])
	@endforeach
@endslider

@if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
    <div class="t-read-more-section u-display--flex u-align-content--center u-margin__y--4">
        @button([
            'text' => __('Show more', 'modularity'),
            'color' => 'secondary',
            'style' => 'filled',
            'href' => $archive_link_url . '?' . http_build_query($filters),
            'classList' => ['u-flex-grow--1@xs'],
        ])
        @endbutton
    </div>
@endif
