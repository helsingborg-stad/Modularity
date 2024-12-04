@if (!empty($url))
    @if (empty($hideTitle) && !empty($postTitle))
        @typography([
            'id'        => 'mod-markdown-' . $ID .'-label',
            'element'   => 'h2', 
            'variant'   => 'h2', 
            'classList' => [
                'module-title',
                'u-text-align--' . $alignment,
            ]
        ])
            {!! $postTitle !!}
        @endtypography
    @endif
@endif

@if($isMarkdownUrl)

    @if($parsedMarkdown)
        {!! $parsedMarkdown !!}

        @if($showMarkdownSource && ($markdownUrl || $markdownLastUpdated))
            @paper(['padding' => '2', 'classList' => ['u-margin__top--4']])
                @if (!empty($markdownUrl))
                    <small class="u-display--block">
                        <strong>{{ $language->sourceUrl }}:</strong> <a href="{{ $markdownUrl }}" class="u-text-decoration--none" target="_blank" rel="noopener noreferrer">
                            {{ $markdownUrl }}
                        </a>
                    </small>
                @endif
                @if (!empty($markdownLastUpdated))
                    <small class="u-display--block">
                        <strong>{{ $language->lastUpdated }}:</strong> {{ $markdownLastUpdated }}
                    </small>
                    <small class="u-display--block">
                        <strong>{{ $language->nextUpdate }}:</strong> {{ $markdownNextUpdate }}
                    </small>
                @endif
            @endpaper
        @endif
    @else 
        @notice([
            'id' => 'mod-markdown-' . $ID .'-notice',
            'type' => 'info',
            'message' => [
                'text' => $language->fetchError,
            ],
            'icon' => [
                'name' => 'report',
                'size' => 'md',
                'color' => 'white'
            ]
        ])
        @endnotice
    @endif

@else 
    @notice([
        'id'        => 'mod-markdown-' . $ID .'-notice',
        'type' => 'info',
        'message' => [
            'text' => $language->parseError,
        ],
        'icon' => [
            'name' => 'report',
            'size' => 'md',
            'color' => 'white'
        ]
    ])
    @endnotice
@endif