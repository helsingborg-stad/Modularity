@slider__item([
    'title'             => $slide['textblock_title'],
    'text'              => $slide['textblock_content'],
    'background_image'  => $slide['image'] ? $slide['image']['src'] : null,
    'layout'            => $slide['textblock_position'],
    'background_video'  => $slide['video_mp4']['url'],
    'containerColor'    => $slide['background_color'] ?? 'none',
    'textColor'         => $slide['text_color'],
    'showPauseButton'   => !$autoslide,
    'cta'               => $slide['call_to_action'] ?? null,
])
@endslider__item

