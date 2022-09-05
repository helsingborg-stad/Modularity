@if (!$hideTitle && !empty($postTitle))
    @typography([
        'id'        => 'mod-slider-' . $ID . '-label',
        'element'   => 'h4', 
        'variant'   => 'h2', 
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif

@slider([
    'autoSlide'     => $autoslide,
    'ratio'         => $ratio ?? '16:9',
    'repeatSlide'   => $wrapAround,
    'shadow'        => $sidebarContext !== 'sidebar.slider-area',
    'heroStyle'     => $sidebarContext === 'sidebar.slider-area',
    'classList'     => [$classes],
    'attributeList' => [
        'aria-labelledby' => 'mod-slider-' . $ID . '-label',
    ],
    'context'       => ['module.slider', $sidebarContext . '.module.slider']
])
    @foreach ($slides as $slide)
        @includeFirst(['partials.' . $slide->acf_fc_layout, 'partials.item'])
    @endforeach
@endslider