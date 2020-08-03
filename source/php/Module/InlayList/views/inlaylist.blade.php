<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
        @typography([
            'element'   => 'h4',
            'variant'   => 'h4',
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif

    @listing([
        'list'          => $listData['list'],
        'elementType'   => 'ul',
        'classList'     => ['nav']
    ])
    @endlisting
</div>