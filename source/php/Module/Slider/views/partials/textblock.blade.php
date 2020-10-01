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
                'text' => $slide->link_text,
                'color' => 'secondary',
                'type' => 'filled',
                'href' => $slide->link_url,
                'target' => $slide->link_type === 'external' ? '_blank' : '_self' 
            ])
            @endbutton
        @endslot
    @endif
    
@endsegment