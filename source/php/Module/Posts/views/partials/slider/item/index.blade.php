@card([
    'link' => $post->permalink,
    'imageFirst' => true,
    'image' => $post->showImage && !empty($post->images['thumbnail16:9']) ? [
        'src' => $post->images['thumbnail16:9']['src'], 
        'alt' => $post->images['thumbnail16:9']['alt']
        ] : [],
    'hasPlaceholder' => !empty($post->hasPlaceholderImage),
    'heading' => !empty($post->showTitle) ? $post->postTitle : false,
    'content' => !empty($post->showExcerpt) ? $post->excerptShort : false,
    'classList' => [!empty($classes) ? $classes : '', 'u-color__text--info', 'c-card--focus-inset'],
    'date' => !empty($post->postDateFormatted) ? $post->postDateFormatted : false,
    'dateBadge' => !empty($post->dateBadge) ? $post->dateBadge : false,
    'containerAware' => false,
    'hasAction' => true,
    'hasFooter' => true,
    'tags' => !empty($post->termsUnlinked) ? $post->termsUnlinked : false,
    'context' => ['module.posts.index'],
    'postId' => $post->id,
    'postType' => $post->post_type ?? '',
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