@slider__item([
    'title'             => $slide->textblock_title,
    'text'              => $slide->textblock_content,
    'background_image'  => $slide->image_use[0],
    'layout'            => $slide->textblock_position,
    'mobile_image'      => $slide->mobile_image_use[0],
    'background_video'  => $slide->video_mp4['url'],
    'containerColor'    => $slide->background_color ?? 'none',
    'textColor'         => $slide->text_color
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
@endslider__item