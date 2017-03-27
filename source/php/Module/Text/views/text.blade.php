@if ($hide_box_frame)

<article class="no-margin full {{ $font_size }}">
    @if (!$hideTitle && !empty($post_title))
        <h1>{!! apply_filters('the_title', $post_title) !!}</h1>
    @endif

    {!! apply_filters('the_content', $post_content) !!}
</article>

@else

<div class="{{ $classes }} {{ $font_size }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <div class="box-content">
        {!! apply_filters('the_content', $post_content) !!}
    </div>
</div>

@endif
