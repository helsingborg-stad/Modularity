@card([
    'classList' => [
        'c-card--panel'
    ],
    'attributeList' => [
        'aria-labelledby' => 'mod-map-' . $id .'-label'
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element' => 'h4', 
                'variant' => 'p',
                'id'      => 'mod-map-' . $id .'-label'
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    <iframe src="{{ $map_url }}" frameborder="0" class="u-width--100 u-display--block" style="height: {{ $height }}px;" title="{{ $map_description }}"></iframe>

    @if($show_button)
        <div class="c-card__footer">
            @button([
                'type' => 'filled',
                'color' => 'primary',
                'text' => $button_label, 
                'size' => 'sm', 
                'attributeList' => ['data-open' => 'modal-' . $uid],
                'classList' => ['u-display--block@xs', 'u-display--block@sm']
            ])
            @endbutton
        </div>

        @modal([
                'id' => 'modal-' . $uid,
                'isPanel' => true,
                'animation' => 'slide-up',
                'padding' => 0,
                'heading' => $post_title
        ])
            <iframe src="{{ $button_url }}" frameborder="0" class="u-width--100 u-display--block" style="height: 100vh;" aria-label="{{ $map_description }}"></iframe>
        @endmodal

    @endif
@endcard