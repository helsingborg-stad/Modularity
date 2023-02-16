<!-- Social media by curator -->

@if (!$hideTitle && !empty($postTitle))
    @typography([
        'id' => 'mod-posts-' . $ID . '-label',
        'element' => 'h2',
        'variant' => 'h2',
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif

@if ($showFeed)
    <div class="o-grid modularity-socialmedia-container">

        @include("partials.$layout", ['posts' => $posts])

        <div class="o-grid-12">
            @button([
                'text' => 'Load more',
                'color' => 'primary',
                'attributeList' => ['offset' => $limit],
            ])
            @endbutton
            @typography(['element' => 'div', 'variant' => 'meta', 'classList' => ['u-text-align--right']])
                <a href="https://curator.io" target="_blank" rel="nofollow">Powered by Curator.io</a>
            @endtypography
        </div>
    </div>
@else
    @notice([
        'type' => 'info',
        'message' => [
            'text' => $errorMessage,
            'size' => 'sm'
        ],
        'icon' => [
            'name' => 'report',
            'size' => 'md',
            'color' => 'white'
        ]
    ])
    @endnotice

@endif
