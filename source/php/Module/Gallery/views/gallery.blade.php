<div>
    @if (!$hideTitle && !empty($post_title))
    <h2>{!! apply_filters('the_title', $post_title) !!}</h2>
    @endif

    <ul class="image-gallery grid grid-gallery">
        @if (isset($mod_gallery_images))
        @foreach ($mod_gallery_images as $image)
        <li class="grid-md-3">
            <a class="{{ $classes }} lightbox-trigger" href="{{ $image['sizes']['large'] }}" data-caption="{{ (isset($image['caption']) && !empty($image['caption']) && !in_array(strtolower($image['caption']), array('caption text'))) ? $image['caption'] : '' }}">
                @if (isset($image['thumbnail']) && $image['thumbnail'])
                <img src="{{ $image['thumbnail'][0] }}" alt="{{ $image['alt'] }}">
                @else
                <figure class="image-placeholder"></figure>
                @endif
            </a>
         </li>
         @endforeach
         @endif
    </ul>
</div>
