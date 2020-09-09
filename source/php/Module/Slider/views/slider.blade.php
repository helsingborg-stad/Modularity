@slider([
    'showStepper' => true,
    'autoSlide' => $c_autoslide
])
    @foreach ($slides as $slide)
        @include('partials.' . $slide->acf_fc_layout)
    @endforeach
@endslider