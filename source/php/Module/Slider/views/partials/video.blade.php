@slider__item([
    'title'             => $slide->textblock_title,
    'text'              => $slide->textblock_content,
    'background_image'  => $slide->image_use[0],
    'layout'            => $slide->textblock_position,
    'background_video'  => $slide->video_mp4['url'],
    'containerColor'    => $slide->background_color ?? 'none',
    'textColor'         => $slide->text_color,
    'cta'               => $slide->call_to_action,
    'showPauseButton'   => !$autoslide
])
@endslider__item