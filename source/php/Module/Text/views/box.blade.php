@card()
    <div class="c-card__body">
        @if (!$hideTitle && !empty($post_title))
            <div class="c-card__heading">
                @typography([
                    "element" => "h2",
                    "variant" => "h2",
                    "classList" => ['c-card__heading']
                ])
                    {{ $post_title }}
                @endtypography
            </div>
        @endif

        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
    </div>
@endcard