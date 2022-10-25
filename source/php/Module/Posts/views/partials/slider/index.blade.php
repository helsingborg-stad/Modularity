@card([
    'link' => $post->link,
    'imageFirst' => true,
    'image' => ['src' => $post->thumbnail[0], 'alt' => $post->post_title],
    'heading' => ($post->showTitle? $post->post_title : false),
    'content' => ($post->showExcerpt ? $post->post_content : false),
    // 'subHeading' => 'index.blade.php',
    'classList' => ['u-color__text--info'],
    'date' => ($post->showDate ? get_post_time( "Y-m-d H:i",  $post ) : false),
    'containerAware' => true,
    // 'hasAction' => true,
    // 'tags' => $post->tags,
    // 'context' => ['module.posts.slider'],
    'context' => ['module.posts.index'],
])
@endcard