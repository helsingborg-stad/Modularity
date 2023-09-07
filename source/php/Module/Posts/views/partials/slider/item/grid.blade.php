@block([
    'heading' => ($post->showTitle ? $post->postTitle : false),
    'content' => ($post->showExcerpt ? $post->excerptShort : false),
    'ratio' => $ratio,
    'meta' => $post->termsUnlinked,
    'date' => $post->postDate,
    'dateBadge' => $post->dateBadge,
    'filled' => true,
    'image' => ($post->showImage ? [
            'src' => $post->thumbnail['src'],
            'alt' => $post->thumbnail['alt'],
            'backgroundColor' => 'secondary',
        ] : false),
    'hasPlaceholder' => $anyPostHasImage && !isset($post->thumbnail['src']),
    'classList' => ['t-posts-block', ' u-height--100'],
    'context' => ['module.posts.block'],
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
