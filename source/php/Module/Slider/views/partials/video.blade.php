@segment([
    'title' => $slide->textblock_title,
    'text' => $slide->textblock_content,
    'background_image' => $slide->image_use[0],
    'background_video' => $slide->video_mp4['url'],
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
                'target' => $slide->link_type === 'external' ? '_blank' : '_self' 
            ])
            @endbutton
        @endslot
    @endif
@endsegment