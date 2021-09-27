@if (!$hideTitle && !empty($postTitle))
    @typography([
        'element' => 'h4',
        'variant' => 'h2',
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<iframe src="{{ $url }}" title="{!! $description ?? apply_filters('the_title', $post_title) !!}" frameborder="0" style="width: 100%; height: {{ $height }}px;"></iframe>
