@if (!$hideTitle && !empty($post_title))
    <h2>{!! apply_filters('the_title', $post_title) !!}</h2>
@endif

@if (isset($mod_image_link_url) && strlen($mod_image_link_url) > 0)
    <a href="{{ $mod_image_link_url }}"><img src="{{ $img_src }}" alt="{{ $mod_image_image['alt'] }}" class="block-level {!! $img_classes !!}"></a>
@else
    <img src="{{ $img_src }}" alt="{{ $mod_image_image['alt'] }}" class="block-level {!! $img_classes !!}">
@endif

@if (isset($mod_image_caption) && !empty($mod_image_caption))
<p class="creamy gutter gutter-sm wp-caption-text">{!! $mod_image_caption !!}</p>
@endif
