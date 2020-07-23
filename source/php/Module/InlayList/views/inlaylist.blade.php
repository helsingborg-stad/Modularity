<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
        <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    @listing([
        'list'          => $listData['list'],
        'elementType'   => 'ul',
        'classList'     => ['nav']
    ])
    @endlisting
</div>