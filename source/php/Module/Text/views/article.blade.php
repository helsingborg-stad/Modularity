<article class="{{ isset($font_size) ? $font_size : '' }}" aria-labelledby="{{'mod-text-' . $ID .'-label'}}">
    
    @if (!$hideTitle && !empty($post_title))
        @typography([
                "variant" => "h2",
                "id" => 'mod-text-' . $ID .'-label'
        ])
                {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif
    
    {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $content)) !!}
</article>
