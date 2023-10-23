@block([
    'heading' => ($post->showTitle ? $post->postTitle : false),
    'content' => ($post->showExcerpt ? $post->excerptShort : false),
    'ratio' => $ratio,
    'meta' => $post->termsUnlinked,
    'date' => $post->postDate,
    'dateBadge' => $post->dateBadge,
    'filled' => true,
    'image' => ($post->showImage && isset($post->images['thumbnail' . $ratio]) ? [
            'src' => $post->images['thumbnail' . $ratio]['src'],
            'alt' => $post->images['thumbnail' . $ratio]['alt'],
            'backgroundColor' => 'secondary',
        ] : false),
    'hasPlaceholder' => !empty($post->hasPlaceholderImage),
    'classList' => ['t-posts-block', ' u-height--100'],
    'context' => ['module.posts.block'],
    'link' => $post->permalink,
    'postId' => $post->id,
    'postType' => $post->postType ?? '',
    'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
    'attributeList' => array_merge($post->attributeList, []),
])
    @slot('floating')
        @if (!empty($post->callToActionItems['floating']))
            @icon($post->callToActionItems['floating'])
            @endicon
        @endif
    @endslot
@endblock
