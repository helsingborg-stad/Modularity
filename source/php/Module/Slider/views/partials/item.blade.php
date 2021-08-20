@slider__item([
    'title'             => $slide->textblock_title,
    'text'              => $slide->textblock_content,
    'desktop_image'     => $slide->image_use[0],
    'layout'            => $slide->textblock_position,
    'background_video'  => $slide->video_mp4['url'] ?? false,
    'containerColor'    => $slide->background_color ?? 'none',
    'textColor'         => $slide->text_color,
    'link'              => $slide->link_url,
    'alt'               => $slide->alt_text,
    'cta'               => $slide->call_to_action,
    'heroStyle'         => $slide->heroStyle,
    'focusPoint'        => $slide->focusPoint, 
])
@endslider__item