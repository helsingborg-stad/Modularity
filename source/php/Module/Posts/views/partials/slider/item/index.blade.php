@card([
    'link' => $post->permalink,
    'imageFirst' => true,
    'image' => $post->showImage ? [
        'src' => $post->thumbnail['src'], 
        'alt' => $post->thumbnail['alt']
        ] : [],
    'hasPlaceholder' => $anyPostHasImage && $post->showImage && !isset($post->thumbnail['src']),
    'heading' => ($post->showTitle ? $post->postTitle : false),
    'content' => ($post->showExcerpt ? $post->excerptShort : false),
    'classList' => [$classes, 'u-color__text--info', 'c-card--focus-inset'],
    'date' => $post->postDate,
    'dateBadge' => $post->dateBadge,
    'containerAware' => false,
    'hasAction' => true,
    'hasFooter' => true,
    'tags' => $post->termsUnlinked,
    'context' => ['module.posts.index'],
    'postId' => $post->id,
    'postType' => $post->post_type ?? '',
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