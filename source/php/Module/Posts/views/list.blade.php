@include('partials.post-filters')

@card([
    'heading' => false,
    'classList' => [$classes],
    'attributeList' => [
        'aria-labelledby' => 'mod-posts-' . $ID . '-label'
    ],
    'context' => 'module.posts.list'
])
@if (!$hideTitle && !empty($postTitle))
<div class="c-card__header">
    @include('partials.post-title', ['variant' => 'h4', 'classList' => []])
</div>
@endif

    @if (!empty($prepareList))
        <div class="o-grid {{ $stretch ? 'o-grid--stretch' : '' }}">
            <div class="o-grid-12">
                @collection([
                    'sharpTop' => true,
                    'bordered' => true
                ])
                    @foreach ($prepareList as $post)
                        @if ($post['href'] && $post['columns'] && $post['columns'][0])
                            @collection__item([
                                'displayIcon' => true,
                                'icon' => 'arrow_forward',
                                'link' => $post['href']
                            ])
                                @typography([
                                    'element' => 'h2',
                                    'variant' => 'h4'
                                ])
                                    {{ $post['columns'][0] }}
                                @endtypography
                            @endcollection__item
                        @endif
                    @endforeach
                @endcollection
            </div>
        </div>
    @endif
@endcard

@if ($posts_data_source !== 'input' && $archive_link_url)
    <div class="t-read-more-section u-display--flex u-align-content--center u-margin__y--4">
        @button([
            'text' => __('Show more', 'modularity'),
            'color' => 'secondary',
            'style' => 'filled',
            'href' => $archive_link_url . '?' . http_build_query($filters),
            'classList' => ['u-flex-grow--1@xs', 'u-margin__x--auto'],
        ])
        @endbutton
    </div>
@endif
