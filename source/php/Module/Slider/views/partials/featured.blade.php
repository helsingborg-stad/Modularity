@segment([
    'title' => $slide->textblock_title,
    'text' => $slide->textblock_content,
    'background_image' => $slide->image_use[0],
    'layout' => $layout,
    'containerColor' => 'tertiary',
])

    @if ($slide->link_url) 
        @slot('bottom')
            @button([
                'size' => 'sm',
                'text' => $slide->link_text,
                'color' => 'default',
                'type' => 'filled',
                'href' => $slide->link_url,
                'target' => '_self',
                'classList' => ['u-margin__top--2']
            ])
            @endbutton
        @endslot
    @endif
    
@endsegment