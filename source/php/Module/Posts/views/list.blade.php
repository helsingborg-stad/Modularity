@include('partials.post-filters')

@card([
    'heading' => apply_filters('the_title', $post_title),
    'classList' => [$classes]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element' => "h4"
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @table([
        'list' => $prepareList,
        'hasHoverEffect' => true,
        'hasZebraStripes' => true,
        'showHeader' => false,
        'showFooter' => false
    ])
    @endtable
@endcard