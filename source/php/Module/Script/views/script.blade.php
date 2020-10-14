@card([
    'heading' => apply_filters('the_title', $post_title),
    'classList' => [$classes]
])
    @if (!$hideTitle && !empty($post_title))
        @typography([
            'element'   => 'h4',
            'variant'   => 'h4',
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif

    {!! $embed !!}
@endcard

