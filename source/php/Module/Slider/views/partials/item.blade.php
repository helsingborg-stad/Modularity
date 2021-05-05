@slider__item([
    'title'             => $slide->textblock_title,
    'text'              => $slide->textblock_content,
    'desktop_image'     => $slide->image_use[0],
    'layout'            => $slide->textblock_position,
    'mobile_image'      => $slide->mobile_image_use[0],
    'background_video'  => $slide->video_mp4['url'] ?? false,
    'containerColor'    => $slide->background_color ?? 'none',
    'textColor'         => $slide->text_color,
    'link'              => $slide->link_url,
    'alt'               => $slide->image['alt'],
    'altMobile'         => $slide->mobile_image['alt'],
    'cta'               => $slide->call_to_action,
    'heroStyle'        => $slide->heroStyle
])
@endslider__item 