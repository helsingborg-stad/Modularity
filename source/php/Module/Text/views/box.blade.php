@card(['classList' => ['c-card--panel']])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                "element" => "h4",
            ])
                {{ $post_title }}
            @endtypography
        </div>
    @endif
    <div class="c-card__body">
        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
    </div>
@endcard