<div class="{{ $classes }} {{ isset($font_size) ? $font_size : '' }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <div class="box-content">
        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content, $ID)) !!}
    </div>
</div>
