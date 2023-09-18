@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}"
@if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
@foreach ($posts as $post)
        <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
            @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'block')
                @block([
                    'heading' => !empty($post->showTitle) ? $post->postTitle : false,
                    'content' => !empty($post->showExcerpt) ? $post->excerptShort : false,
                    'ratio' => '16:9',
                    'meta' => !empty($termsUnlinked) ? $post->termsUnlinked : false,
                    'secondary_meta' => $display_reading_time ? $post->readingTime : false,
                    'date' => !empty($post->postDate) ? $post->postDate : false,
                    'dateBadge' => !empty($post->dateBadge) ? $post->dateBadge : false,
                    'filled' => true,
                    'image' =>
                        $post->showImage && isset($post->thumbnail['src'])
                            ? [
                                'src' => $post->thumbnail['src'],
                                'alt' => $post->thumbnail['alt'],
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
                    @if (!empty($post->floating['floating']))
                        @icon($post->floating['floating'])
                        @endicon
                    @endif
                @endslot
                @endblock
            @else
            @php
            @endphp
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
                        $post->showImage && isset($post->thumbnail['src'])
                            ? [
                                'src' => $post->thumbnail['src'],
                                'alt' => $post->thumbnail['alt'],
                                'backgroundColor' => 'secondary'
                            ]
                            : [],
                    'postId' => $post->id,
                    'postType' => $post->postType ?? '',
                    'icon' => !empty($post->termIcon['icon']) ? $post->termIcon : false,
                    'attributeList' => array_merge($post->attributeList, []),
                ])
                    @slot('floating')
                        @if (!empty($post->floating['floating']))
                            @icon($post->floating['floating'])
                            @endicon
                        @endif
                    @endslot
                @endcard
            @endif
        </div>
    @endforeach
</div>

@include('partials.more')
