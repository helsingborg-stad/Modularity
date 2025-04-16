@card([
    'attributeList' => [
        ...(!$hideTitle && !empty($postTitle) ? ['aria-labelledby' => 'mod-text-' . $ID . '-label'] : []),
    ],
    'context' => 'module.text.box'
])
    @if (empty($hideTitle) && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                "element" => "h2",
                "variant" => "h4",
                'id' => 'mod-text-' . $ID .'-label'
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif
    <div class="c-card__body">
        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
    </div>
@endcard