@include('partials.post-filters')

@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid 
    {{ $stretch ? 'o-grid--stretch' : '' }} 
    {{ $noGutter ? 'o-grid--no-gutter' : '' }}"
    @if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
    @foreach ($posts as $post)
        <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
            @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'card')
                @card([
                    'link' => $post->link,
                    'imageFirst' => true,
                    'heading' => $post->post_title,
                    'hasFooter' => $post->tags ? true : false,
                    'context' => ['module.posts.index'],
                    'content' => $post->post_content,
                    'meta' => $display_reading_time ? $post->reading_time : false,
                    'tags' => $post->tags,
                    'date' => $post->showDate
                        ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
                        : false,
                    'containerAware' => true,
                    'hasAction' => true,
                    'hasPlaceholder' => $anyPostHasImage && $post->showImage && !isset($post->thumbnail[0]),
                    'image' => $post->showImage
                        ? [
                            'src' => $post->thumbnail[0],
                            'alt' => $post->post_title,
                            'backgroundColor' => 'secondary'
                        ]
                        : [],
                    'postId' => $post->ID,
                    'postType' => $post->post_type ?? '',
                    'icon' => $post->termIcon['icon'] ? $post->termIcon : false
                ])
                 @slot('floating')
                    @if (!empty($post->floating['floating']))
                        @icon($post->floating['floating'])
                        @endicon
                    @endif
                @endslot
                @endcard
            @else
                @block([
                    'heading' => $post->showTitle ? $post->post_title : false,
                    'content' => $post->showExcerpt ? $post->post_content : false,
                    'ratio' => $ratio,
                    'meta' => $post->tags ? $post->tags : false,
                    'secondaryMeta' => $display_reading_time ? $post->reading_time : false,
                    'date' => $post->showDate
                        ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
                        : false,
                    'filled' => true,
                    'image' => $post->showImage
                        ? [
                            'src' => $post->thumbnail[0],
                            'alt' => $post->post_title,
                            'backgroundColor' => 'secondary'
                        ]
                        : false,
                    'hasPlaceholder' => $anyPostHasImage && !isset($post->thumbnail[0]),
                    'classList' => ['t-posts-block', ' u-height--100'],
                    'context' => 'module.posts.block',
                    'link' => $post->link,
                    'postId' => $post->ID,
                    'postType' => $post->post_type ?? '',
                    'icon' => $post->termIcon['icon'] ? $post->termIcon : false
                ])
                @slot('floating')
                    @if (!empty($post->floating['floating']))
                        @icon($post->floating['floating'])
                        @endicon
                    @endif
                @endslot
                @endblock
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
