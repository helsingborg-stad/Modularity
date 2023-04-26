@include('partials.post-filters')

@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid {{ $stretch ? 'o-grid--stretch' : '' }} {{ $noGutter ? 'o-grid--no-gutter' : '' }}"
    aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="o-grid-12">

            @card([
                'link' => $post->link,
                'classList' => $classes,
                'hasFooter' => $post->tags ? true : false,
                'meta' => $display_reading_time ? $post->reading_time : false,
                'context' => 'module.posts.index',
                'containerAware' => true,
                'hasAction' => true,
                'date' => '2022-01-14',
                'postId' => $post->ID,
                'postType' => $post->post_type ?? '',
                'icon' => $icon
            ])
                @if ($post->showImage && isset($post->thumbnail[0]) && !empty($post->thumbnail[0]))
                    <div class="c-card__image c-card__image--secondary">
                        <div class="c-card__image-background u-ratio-16-9" alt="{{ $post->post_title }}"
                            style="background-image:url('{{ $post->thumbnail[0] }}');"></div>
                    </div>
                @endif

                <div class="c-card__body">
                    @if ($post->showTitle)
                        @typography([
                            'element' => 'h2',
                            'classList' => ['c-card__heading']
                        ])
                            {{ $post->post_title }}
                        @endtypography
                    @endif

                    @includeWhen($post->showDate, 'partials.date')

                    {!! $post->post_content !!}

                </div>
                @if ($post->tags)
                    <div class="c-card__footer">
                        @tags
                            (['tags' => $post->tags])
                        @endtags
                    </div>
                @endif
            @endcard

        </div>
    @endforeach
</div>


@if ($posts_data_source !== 'input' && $archive_link_url)
    <div class="t-read-more-section u-display--flex u-align-content--center u-margin__y--4">
        @button([
            'text' => __('Show more', 'modularity'),
            'color' => 'secondary',
            'style' => 'filled',
            'href' => $archive_link_url . '?' . http_build_query($filters),
            'classList' => ['u-flex-grow--1@xs'],
        ])
        @endbutton
    </div>
@endif
