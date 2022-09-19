@if (!$hideTitle && !empty($postTitle))
    @typography([
        'element' => 'h4',
        'variant' => 'h2',
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

@iframe([
<<<<<<< HEAD
	'src' => $url,
	'height' => $height,
	'title' => $description ?? $post_title,
=======
	  'src' => $url,
	  'height' => $height,
    'width' => '100%',
    'frameborder' => '0',
	  'title' => $description ?? $post_title,
>>>>>>> iframe-info-text-varibales
    'classList' => ['js-suppressed-iframe'],
    'data-suppressed-iframe-options' => json_encode($suppressedIframeOptions),
])
@endiframe

