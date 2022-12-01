@card([
    'attributeList' => [
        'aria-labelledby' => 'mod-text-' . $ID . '-' . $uid . '-label'
    ],
    'context' => 'module.text.box'
])
    @if (!$hideTitle && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                "element" => "h4",
                'id' => 'mod-text-' . $ID . '-' . $uid . '-label'
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif
    <div class="c-card__body">
        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
    </div>
@endcard
