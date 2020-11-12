@if (!$hideTitle && !empty($post_title))
    @typography([
        'element' => "h4",
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif


@slider([
    'showStepper' => true,
    'autoSlide' => $c_autoslide
])
    @foreach ($slides as $slide)
        @include('partials.' . $slide->acf_fc_layout, ['layout' => $slide_align])
    @endforeach
@endslider