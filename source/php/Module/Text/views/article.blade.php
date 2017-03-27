<article class="no-margin full {{ $font_size }}">
    @if (!$hideTitle && !empty($post_title))
        <h1>{!! apply_filters('the_title', $post_title) !!}</h1>
    @endif

    {!! apply_filters('the_content', $post_content) !!}
</article>
