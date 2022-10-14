@slider__item([
    'title'         => $post->post_title,
    'desktop_image' => $post->thumbnail[0],	
	'containerColor'   => 'none',
	'overlay'          => 'dark',
	'textColor'        => 'white',
	'attributeList'          => [
		'classList' => [$baseClass .'s__slider__item']
	]
])
@endslider__item
