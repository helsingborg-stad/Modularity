@card([
    'heading' => apply_filters('the_title', $post_title),
    'context' => 'module.script'
])
    @if (!$hideTitle && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                'element' => 'h4',
                'classList' => ['card-title']
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif

    @if (is_array($embed))
        @foreach ($embed as $embeddedContent)
            @if ($embeddedContent['requiresAccept'])
                @acceptance([
                    'labels' => json_encode($lang),
                    'src' => !empty($embeddedContent['src']) ? $embeddedContent['src'] : null,
                    'modifier' => 'script'
                ])
            @endif
                <div class="{{ $scriptPadding }}">{!! $embeddedContent['content'] !!}</div>

            @if ($embeddedContent['requiresAccept'])
                @endacceptance
            @endif
        @endforeach
    @else
        @if ($requiresAccept)
            @acceptance([
                'labels' => json_encode($lang),
                'modifier' => 'script'
            ])
        @endif
            <div class="{{ $scriptPadding }}">{!! $embed !!}</div>
        @if ($requiresAccept)
            @endacceptance
        @endif
    @endif
    @image([
        'src' => $placeholder['url'],
        'alt' => $placeholder['alt'],
        'classList' => ['box-image', 'u-print-display--inline-block', 'u-display--none']
    ])
    @endimage
@endcard
