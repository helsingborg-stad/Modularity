@card([
    'link' => $post->link,
    'imageFirst' => true,
    'image' => $post->showImage ? [
        'src' => $post->thumbnail[0], 
        'alt' => $post->post_title
        ] : [],
    'hasPlaceholder' => $anyPostHasImage && $post->showImage && !isset($post->thumbnail[0]),
    'heading' => ($post->showTitle ? $post->post_title : false),
    'content' => ($post->showExcerpt ? $post->post_content : false),
    'classList' => [$classes, 'u-color__text--info', 'c-card--focus-inset'],
    'date' => $post->showDate ? $post->post_date : false,
    'dateBadge' => $post->dateBadge,
    'containerAware' => false,
    'hasAction' => true,
    'hasFooter' => true,
    'tags' => $post->tags,
    'context' => ['module.posts.index'],
    'postId' => $post->ID,
    'postType' => $post->post_type ?? '',
    'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
])
    @slot('floating')
        @if (!empty($post->floating['floating']))
            @icon($post->floating['floating'])
            @endicon
        @endif
    @endslot
@endcard