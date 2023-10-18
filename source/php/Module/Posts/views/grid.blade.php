@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}"
    @if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
    @if($posts)
        @foreach ($posts as $post)
            <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
                @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'card')
                    @card([
                        'link' => $post->permalink,
                        'imageFirst' => true,
                        'heading' => $post->postTitle,
                        'hasFooter' => !empty($post->termsUnlinked) ? true : false,
                        'context' => ['module.posts.index'],
                        'content' => isset($post->showExcerpt) ? $post->excerptShort : '',
                        'meta' => $display_reading_time ? $post->readingTime : false,
                        'tags' => !empty($post->termsUnlinked) ? $post->termsUnlinked : false,
                        'date' => $post->postDate,
                        'dateBadge' => !empty($post->dateBadge) ? $post->dateBadge : false,
                        'containerAware' => true,
                        'hasAction' => true,
                        'hasPlaceholder' => !empty($post->hasPlaceholderImage),
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
                        'secondaryMeta' => $display_reading_time ? $post->readingTime : false,
                        'date' => $post->postDate,
                        'dateBadge' => $post->dateBadge,
                        'filled' => true,
                        'image' => $post->showImage
                            ? [
                                'src' => $post->thumbnailSquare['src'],
                                'alt' => $post->thumbnailSquare['alt'],
                                'backgroundColor' => 'secondary'
                            ]
                            : false,
                        'hasPlaceholder' => !empty($post->hasPlaceholderImage),
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
    @endif
</div>

@include('partials.more')
