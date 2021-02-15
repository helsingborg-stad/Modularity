@card([
    'classList' => ['c-card--panel'],
    'attributeList' => [
        'aria-labelledby' => 'mod-text-' . $ID .'-label'
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                "element" => "h4",
                'id' => 'mod-text-' . $ID .'-label'
            ])
                {{ $post_title }}
            @endtypography
        </div>
    @endif
    <div class="c-card__body">
        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
    </div>
@endcard