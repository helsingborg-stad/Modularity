<div class="box box-filled">
    @if (isset($mod_image_link_url) && strlen($mod_image_link_url) > 0)
        <a href="{{ $mod_image_link_url }}"><img src="{{ $img_src }}" alt="{{ $mod_image_image['alt'] }}" class="box-image {!! $img_classes !!}"></a>
    @else
        <img src="{{ $img_src }}" alt="{{ $mod_image_image['alt'] }}" class="box-image {!! $img_classes !!}">
    @endif

    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    @if (isset($mod_image_caption) && !empty($mod_image_caption))
    <div class="box-content">
        {!! $mod_image_caption !!}
    </div>
    @endif
</div>
