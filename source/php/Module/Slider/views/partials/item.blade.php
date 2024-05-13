@slider__item([
    'title'             => $slide['textblock_title'],
    'text'              => $slide['textblock_content'],
    'layout'            => $slide['textblock_position'],
    'desktop_image'     => $slide['image']['src'],
    'background_video'  => $slide['video_mp4']['url'] ?? false,
    'link'              => $slide['link_url'],
    'linkDescription'   => $slide['link_url_description'],
    'alt'               => $slide['image']['alt'],
    'heroStyle'         => $sidebarContext === 'sidebar.slider-area',
    'cta'               => $slide['call_to_action'] ?? null,
    'focusPoint'        => $slide['focusPoint'],
    'context'           => ['module.slider-item', $sidebarContext . '.module.slider-item']
])
@endslider__item