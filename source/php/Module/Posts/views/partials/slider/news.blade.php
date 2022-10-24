@card([
    // 'heading' => $post->post_title,
    'heading'   => 'NEWS',
    'subHeading' => 'news.blade.php',
    'classList' => [$classes, 'u-color__text--info'],
    'date' => $post->post_date,
    'image' => ['src' => $post->thumbnail[0], 'alt' => $post->post_title],
    'imageFirst' => true,
    // 'link' => $post->link,
    'containerAware' => true,
    'hasAction' => true,
    'tags' => $post->tags,
    'context' => ['module.posts.slider'],
    ])
@endcard