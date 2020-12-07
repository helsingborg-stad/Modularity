@slider__item([
    'title'             => $slide->textblock_title,
    'text'              => $slide->textblock_content,
    'background_image'  => $slide->image_use[0],
    'layout'            => $slide->textblock_position,
    'background_image'  => $slide->image_use[0],
    'mobile_image'      => $slide->mobile_image_use[0],
    'background_video'  => $slide->video_mp4['url'],
    'containerColor'    => $slide->background_color ?? 'none',
    'textColor'         => $slide->text_color,
    'link'              => $slide->link_url
])
    @if($slider->type === 'embeded')
        {!! apply_filters('the_content', $slider->embed_link) !!}
    @endif
@endslider__item