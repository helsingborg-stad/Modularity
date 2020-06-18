<article class="no-margin full {{ isset($font_size) ? $font_size : '' }}">
    @if (!$hideTitle && !empty($post_title))
        @typography([
                "variant" => "h2"
        ])
                {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif

    {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
</article>
