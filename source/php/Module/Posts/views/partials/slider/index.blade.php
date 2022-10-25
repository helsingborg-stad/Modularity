@card([
    'heading' => $post->post_title,
    // 'subHeading' => 'index.blade.php',
    'classList' => ['u-color__text--info'],
    'date' => ($post->showDate ? get_post_time( "Y-m-d H:i",  $post ) : false),
    'image' => ['src' => $post->thumbnail[0], 'alt' => $post->post_title],
    'imageFirst' => true,
    'link' => $post->link,
    'containerAware' => true,
    // 'hasAction' => true,
    // 'tags' => $post->tags,
    // 'context' => ['module.posts.slider'],
    'context' => ['module.posts.index'],
])
@endcard