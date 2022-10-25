@card([
    'link' => $post->link,
    'image' => $post->showImage ? [
        'src' => $post->thumbnail[0], 
        'alt' => $post->post_title
        ] : [],
    'heading' => ($post->showTitle ? $post->post_title : false),
    'content' => ($post->showExcerpt ? $post->post_content : false),
    'classList' => ['u-color__text--info', 'c-card--slider', 'c-card--size-xs c-card--size-sm c-card--size-md c-card--size-lg'],
    'date' => ($post->showDate ? get_post_time( "Y-m-d H:i",  $post ) : false),
    'containerAware' => true,
    'hasAction' => true,
    // 'tags' => $post->tags,
    // 'context' => ['module.posts.slider'],
])
@endcard