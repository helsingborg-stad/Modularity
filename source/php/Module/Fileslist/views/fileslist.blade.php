<div class="{{ $classes }}" js-filter-container="abc">
    
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    @table([
        'list' => $columnData,
        'headings' => $headings,
        'showFooter' => false,
    ])
    @endtable
    
</div>


