
@card(['heading' => !$hideTitle && $post_title != "" ? $post_title : ''])
    {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
@endcard