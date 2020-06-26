@include('partials.post-filters')

<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))

        @typography([
            'element' => "h4",
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography

    @endif

    @listing([
        'list' => $prepareList
    ])
    @endlisting

</div>