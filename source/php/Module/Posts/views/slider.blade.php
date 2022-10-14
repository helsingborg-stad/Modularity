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
    'id' => isset($blockData['anchor']) ? $blockData['anchor'] : 'mod-posts-' . $ID,
    'showStepper' => $showStepper,
	'autoSlide' => $autoSlide,
    'attributeList' => [
        'aria-labelledby' => 'mod-slider-' . $ID . '-label',
        'data-slides-per-page' => $slidesPerPage
    ]
])
    @foreach ($posts as $post)
        @slider__item([
            'title' => $post->post_title,
            'desktop_image' => isset( $post->thumbnail[0] ) ? $post->thumbnail[0] : false,
            'containerColor' => 'none',
            'overlay' => 'dark',
            'textColor' => 'white',
            
        ])
        @endslider__item
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
