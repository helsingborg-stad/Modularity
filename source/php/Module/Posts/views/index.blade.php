@include('partials.post-filters')

@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid 
    {{ $stretch ? 'o-grid--stretch' : '' }} 
    {{ $noGutter ? 'o-grid--no-gutter' : '' }}"
    @if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
    @foreach ($posts as $post)
        <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">

            @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'block')
                @block([
                    'heading' => $post->showTitle ? $post->post_title : false,
                    'content' => $post->showExcerpt ? $post->post_content : false,
                    'ratio' => '16:9',
                    'meta' => $post->tags,
                    'secondary_meta' => $display_reading_time ? $post->reading_time : false,
                    'date' => $post->showDate
                        ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
                        : false,
                    'filled' => true,
                    'image' =>
                        $post->showImage && isset($post->thumbnail[0])
                            ? [
                                'src' => get_the_post_thumbnail_url($post->ID, [$post->thumbnail[1] * 2, $post->thumbnail[2] * 2]),
                                'alt' => $contact['full_name'],
                                'backgroundColor' => 'secondary'
                            ]
                            : false,
                    'hasPlaceholder' => $anyPostHasImage && !isset($post->thumbnail[0]),
                    'classList' => $display_reading_time
                        ? ['t-posts-block', 't-posts-block--with-reading-time', ' u-height--100']
                        : ['t-posts-block', ' u-height--100'],
                    'context' => 'module.posts.block',
                    'link' => $post->link,
                    'postId' => $post->ID,
                    'postType' => $post->post_type ?? '',
                    'icon' => $post->termIcon
                ])
                    @slot('floating')
                        @includeWhen(!empty($floatingIcon), 'partials.icon')
                    @endslot
                @endblock
            @else
                @card([
                    'link' => $post->link,
                    'imageFirst' => true,
                    'image' => $post->thumbnail,
                    'heading' => $post->showTitle ? $post->post_title : false,
                    'classList' => $classes,
                    'context' => ['module.posts.index'],
                    'content' => $post->showExcerpt ? $post->post_content : false,
                    'tags' => $post->tags,
                    'meta' => $display_reading_time ? $post->reading_time : false,
                    'date' => $post->showDate
                        ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
                        : false,
                    'classList' => $display_reading_time ? ['c-card--with-reading-time', 'u-height--100'] : ['u-height--100'],
                    'containerAware' => true,
                    'hasAction' => true,
                    'hasPlaceholder' => $anyPostHasImage && $post->showImage && !isset($post->thumbnail[0]),
                    'image' =>
                        $post->showImage && isset($post->thumbnail[0])
                            ? [
                                'src' => $post->thumbnail[0],
                                'alt' => $post->post_title,
                                'backgroundColor' => 'secondary'
                            ]
                            : [],
                    'postId' => $post->ID,
                    'postType' => $post->post_type ?? '',
                    'icon' => $post->termIcon
                ])
                    @slot('floating')
                        @includeWhen(!empty($post->floatingIcon), 'partials.icon')
                    @endslot
                @endcard
            @endif
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
            'classList' => ['u-flex-grow--1@xs', 'u-margin__x--auto'],
        ])
        @endbutton
    </div>
@endif
