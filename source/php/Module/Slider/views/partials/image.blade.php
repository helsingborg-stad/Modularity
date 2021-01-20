@segment([
    'title' => isset($slide->activate_textblock) && $slide->activate_textblock ? $slide->textblock_title : false,
    'text' => isset($slide->activate_textblock) && $slide->activate_textblock ? $slide->textblock_content : false,
    'background_image' => $slide->image_use[0],
    'layout' => $layout,
    'containerColor' => 'tertiary',
])

    @if ($slide->link_url) 
        @slot('bottom')
            @button([
                'text' => $slide->link_text,
                'color' => 'default',
                'type' => 'filled',
                'href' => $slide->link_url,
                'target' => '_self'
            ])
            @endbutton
        @endslot
    @endif
@endsegment