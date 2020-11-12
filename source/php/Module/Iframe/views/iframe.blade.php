@if (!$hideTitle && !empty($post_title))
    @typography([
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<iframe src="{{ $url }}" frameborder="0" style="width: 100%; height: {{ $height }}px;"></iframe>