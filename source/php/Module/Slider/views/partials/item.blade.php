@slider__item([
    'title'             => $slide->textblock_title,
    'text'              => $slide->textblock_content,
    'desktop_image'     => $slide->image_use[0],
    'layout'            => $slide->textblock_position,
    'background_video'  => $slide->video_mp4['url'] ?? false,
    'link'              => $slide->link_url,
    'alt'               => $slide->alt_text,
    'cta'               => $slide->call_to_action,
    'heroStyle'         => $slide->heroStyle,
    'focusPoint'        => $slide->focusPoint,
    'context'           => ['module.slider-item', $sidebarContext . '.module.slider-item']
])
@endslider__item
