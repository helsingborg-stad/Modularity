@card([
    'link' => $post->permalink,
    'imageFirst' => true,
    'image' => $post->showImage ? [
        'src' => $post->thumbnail['src'], 
        'alt' => $post->thumbnail['alt']
        ] : [],
    'hasPlaceholder' => !empty($post->hasPlaceholderImage),
    'heading' => !empty($post->showTitle) ? $post->postTitle : false,
    'content' => !empty($post->showExcerpt) ? $post->excerptShort : false,
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
        @if (!empty($post->callToActionItems['floating']))
            @icon($post->callToActionItems['floating'])
            @endicon
        @endif
    @endslot
@endcard