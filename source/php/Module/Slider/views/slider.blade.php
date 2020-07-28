@slider([
    'showStepper' => true
])
    @foreach ($slides as $slide)
        @include('partials.' . $slide->acf_fc_layout)
    @endforeach
@endslider