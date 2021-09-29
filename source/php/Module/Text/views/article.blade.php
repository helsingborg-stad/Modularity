<article class="{{ isset($font_size) ? $font_size : '' }}" aria-labelledby="{{'mod-text-' . $ID .'-label'}}">
    
    @if (!$hideTitle && !empty($postTitle))
        @typography([
                "variant" => "h2",
                "id" => 'mod-text-' . $ID .'-label'
        ])
                {!! $postTitle !!}
        @endtypography
    @endif
    
    {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
</article>
