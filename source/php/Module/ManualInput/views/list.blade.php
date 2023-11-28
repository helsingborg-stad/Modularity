@card([
    'classList' => ['o-grid', !empty($stretch) ? 'o-grid--stretch' : ''],
    'context' => $context,
])
    @if (empty($hideTitle) && !empty($custom_block_title))
    <div class="c-card__header">
        @include('partials.post-title', ['variant' => 'h4', 'classList' => []])
    </div>
    @endif
    @if (!empty($manualInputs))
        @collection([
            'sharpTop' => true,
            'bordered' => true
        ])
            @foreach ($manualInputs as $input)
                @collection__item([
                    'displayIcon' => true,
                    'icon' => 'arrow_forward',
                    'link' => $input['link']
                ])
                    @typography([
                        'element' => 'h2',
                        'variant' => 'h4'
                    ])
                        {{ $input['title'] }}
                    @endtypography
                @endcollection__item
            @endforeach
        @endcollection
    @endif
@endcard