@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}"
@if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
    @if($posts)    
        @foreach ($posts as $post)
            <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
                @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'block')
                    @block([
                        'heading' => !empty($post->showTitle) ? $post->postTitle : false,
                        'content' => !empty($post->showExcerpt) ? $post->excerptShort : false,
                        'ratio' => '16:9',
                        'meta' => !empty($post->termsUnlinked) ? $post->termsUnlinked : false,
                        'secondary_meta' => $display_reading_time ? $post->readingTime : false,
                        'date' => !empty($post->postDate) ? $post->postDate : false,
                        'dateBadge' => !empty($post->dateBadge) ? $post->dateBadge : false,
                        'filled' => true,
                        'image' =>
                            $post->showImage && isset($post->images['thumbnail16:9']['src'])
                                ? [
                                    'src' => $post->images['thumbnail16:9']['src'],
                                    'alt' => $post->images['thumbnail16:9']['alt'],
                                    'backgroundColor' => 'secondary'
                                ]
                                : false,
                        'hasPlaceholder' => !empty($post->hasPlaceholderImage),
                        'classList' => $display_reading_time
                            ? ['t-posts-block', 't-posts-block--with-reading-time', ' u-height--100']
                            : ['t-posts-block', ' u-height--100'],
                        'context' => 'module.posts.block',
                        'link' => $post->permalink,
                        'postId' => $post->id,
                        'postType' => $post->postType ?? '',
                        'icon' => !empty($post->termIcon['icon']) ? $post->termIcon : false,
                        'attributeList' => array_merge($post->attributeList, []),
                    ])
                    @slot('floating')
                        @if (!empty($post->callToActionItems['floating']))
                            @icon($post->callToActionItems['floating'])
                            @endicon
                        @endif
                    @endslot
                    @endblock
                @else
                    @card([
                        'link' => $post->permalink,
                        'imageFirst' => true,
                        'heading' => !empty($post->showTitle) ? $post->postTitle : false,
                        'context' => ['module.posts.index'],
                        'content' => !empty($post->showExcerpt) ? $post->excerptShort : false,
                        'tags' => !empty($post->termsUnlinked) ? $post->termsUnlinked : false,
                        'meta' => !empty($display_reading_time) ? $post->readingTime : false,
                        'date' => !empty($post->postDate) ? $post->postDate : false,
                        'dateBadge' => !empty($post->dateBadge) ? $post->dateBadge : false,
                        'classList' => $display_reading_time ? ['c-card--with-reading-time', 'u-height--100'] : ['u-height--100'],
                        'containerAware' => true,
                        'hasAction' => true,
                        'hasPlaceholder' => !empty($post->hasPlaceholderImage),
                        'image' =>
                            $post->showImage && isset($post->images['thumbnail16:9'])
                                ? [
                                    'src' => $post->images['thumbnail16:9']['src'],
                                    'alt' => $post->images['thumbnail16:9']['alt'],
                                    'backgroundColor' => 'secondary'
                                ]
                                : [],
                        'postId' => $post->id,
                        'postType' => $post->postType ?? '',
                        'icon' => !empty($post->termIcon['icon']) ? $post->termIcon : false,
                        'attributeList' => array_merge($post->attributeList, []),
                    ])
                        @slot('floating')
                            @if (!empty($post->callToActionItems['floating']))
                                @icon($post->callToActionItems['floating'])
                                @endicon
                            @endif
                        @endslot
                    @endcard
                @endif
            </div>
        @endforeach
    @endif
</div>

@include('partials.more')
