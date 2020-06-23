@slider([
    'showStepper' => true
])
    @foreach ($slides as $slide)
        @segment([
            'title' => $slide['textblock_title'],
            'text' => $slide['textblock_content'],
            'background_image' => $slide['image_use'][0],
            'overlay' => 'light',
            'overlay_opacity' => 'high'
        ])

            @if ($slide['link_url']) 
                @slot('bottom')
                    @button([
                        'text' => __('Read more', 'modularity'),
                        'color' => 'primary',
                        'type' => 'filled',
                        'href' => $slide['link_url'],
                        'target' => $slide['link_type'] === 'external' ? '_blank' : '_self' 
                    ])
                    @endbutton
                @endslot
            @endif
            
        @endsegment
    @endforeach
@endslider