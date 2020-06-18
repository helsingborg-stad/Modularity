<div class="{{ $classes }} {{ isset($font_size) ? $font_size : '' }}">
    @if (!$hideTitle && !empty($post_title))
        @typography([
            'variant' => "h4",
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif

    <div class="box-content">
        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
    </div>
</div>
