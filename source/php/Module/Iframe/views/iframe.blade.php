@if (!$hideTitle && !empty($postTitle))
    @typography([
        'element' => 'h4',
        'variant' => 'h2',
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<iframe class="js-suppressed-iframe" loading="lazy" src="{{ $url }}" height="{{$height}}" width="100%" title="{!! $description ?? apply_filters('the_title', $post_title) !!}" frameborder="0"></iframe>
