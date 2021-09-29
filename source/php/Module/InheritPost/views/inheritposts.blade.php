@if ($inherit->post_status == 'publish') :
<article>
    @if (!$hideTitle && !empty($postTitle))
            @typography([
                'element' => 'h1'
            ])
                {!! $module->post_title !!}
            @endtypography
    @endif
    @typography([
                'element' => 'h2'
    ])
        {!!  apply_filters('the_title', $inherit->post_title) !!}
    @endtypography

    {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $this->post_content)) !!}
</article>
@endif
