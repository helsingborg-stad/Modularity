@card([
    'classList' => [
        'c-card--panel'
    ]
])

    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element' => 'h4', 
                'variant' => 'h2'
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    <iframe src="{{ $map_url }}" frameborder="0" class="u-width--100 u-display--block" style="height: {{ $height }}px;"></iframe>

    @if($map_url_large)
        <div class="c-card__footer">
            @button(['type' => 'filled', 'color' => 'primary', 'text' => $button_label, 'size' => 'sm', 'href' => $map_url_large])
            @endbutton
        </div>
    @endif
@endcard