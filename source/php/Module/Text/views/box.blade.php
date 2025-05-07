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
    
    @if($postContent)
        <div class="c-card__body">
            {!! $postContent !!}
        </div>
    @endif
@endcard