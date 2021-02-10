@card([
    'classList' => [
        'c-card--panel'
    ]
])

    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element' => 'h4', 
                'variant' => 'p'
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    <iframe src="{{ $map_url }}" frameborder="0" class="u-width--100 u-display--block" style="height: {{ $height }}px;" aria-label="{{ $map_description }}"></iframe>

    @if($show_button)
        <div class="c-card__footer">
            @button([
                'type' => 'filled', 
                'color' => 'primary', 
                'text' => $button_label, 
                'size' => 'sm', 
                'href' => $button_url,
                'classList' => ['u-display--block@xs', 'u-display--block@sm']
            ])
            @endbutton
        </div>
    @endif
@endcard