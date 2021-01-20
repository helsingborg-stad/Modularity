@if (!$hideTitle && !empty($post_title))
    @typography([
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

@slider([
    'autoSlide'     => $autoslide,
    'ratio'         => $ratio ?? '16:9',
    'repeatSlide'   => $wrapAround
])
    @foreach ($slides as $slide)
        @includeFirst(['partials.' . $slide->acf_fc_layout, 'partials.item']); 
    @endforeach
@endslider