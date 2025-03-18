@element([
    'element' => 'article',
    'attributeList' => [
        ...($font_size) ? ['class' => $font_size] : []),
        ...(!$hideTitle && !empty($postTitle) ? ['aria-labelledby' => 'mod-text-' . $ID . '-label'] : []),
    ]
])
    
    @if (!$hideTitle && !empty($postTitle))
        @typography([
                "variant" => "h2",
                "element" => "h4",
                "id" => 'mod-text-' . $ID .'-label'
        ])
                {!! $postTitle !!}
        @endtypography
    @endif
    
    {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
@endelement