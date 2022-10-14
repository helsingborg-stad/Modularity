@slider__item([
    'title'         => $post->post_title,
    'sub_title'          => get_the_excerpt($post->ID),
    'desktop_image' => get_the_post_thumbnail_url($post->ID),
	
	'containerColor'   => 'none',
	'overlay'          => 'dark',
	'textColor'        => 'white',
	'attributeList'          => [
		'classList' => [$baseClass .'s__slider__item']
	]
])
@endslider__item
