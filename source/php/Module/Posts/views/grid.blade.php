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
                    'link' => $post->permalink,
                    'imageFirst' => true,
                    'heading' => $post->postTitle,
                    'hasFooter' => $post->termsUnlinked ? true : false,
                    'context' => ['module.posts.index'],
                    'content' => $post->excerptShort,
                    'meta' => $display_readingtime ? $post->readingTime : false,
                    'tags' => $post->termsUnlinked,
                    'date' => $post->postDate,
                    'dateBadge' => $post->dateBadge,
                    'containerAware' => true,
                    'hasAction' => true,
                    'hasPlaceholder' => $anyPostHasImage && $post->showImage && !isset($post->thumbnail['src']),
                    'image' => $post->showImage
                        ? [
                            'src' => $post->thumbnail['src'],
                            'alt' => $post->thumbnail['alt'],
                            'backgroundColor' => 'secondary'
                        ]
                        : [],
                    'postId' => $post->id,
                    'postType' => $post->postType ?? '',
                    'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
                    'attributeList' => array_merge($post->attributeList, []),
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
                    'heading' => $post->showTitle ? $post->postTitle : false,
                    'content' => $post->showExcerpt ? $post->excerptShort : false,
                    'ratio' => $ratio,
                    'meta' => $post->termsUnlinked ? $post->termsUnlinked : false,
                    'secondaryMeta' => $display_readingtime ? $post->readingTime : false,
                    'date' => $post->postDate,
                    'dateBadge' => $post->dateBadge,
                    'filled' => true,
                    'image' => $post->showImage
                        ? [
                            'src' => $post->thumbnail['src'],
                            'alt' => $post->thumnnail['alt'],
                            'backgroundColor' => 'secondary'
                        ]
                        : false,
                    'hasPlaceholder' => $anyPostHasImage && !isset($post->thumbnail['src']),
                    'classList' => ['t-posts-block', ' u-height--100'],
                    'context' => 'module.posts.block',
                    'link' => $post->permalink,
                    'postId' => $post->id,
                    'postType' => $post->postType ?? '',
                    'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
                    'attributeList' => array_merge($post->attributeList, []),
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
