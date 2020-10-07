
@card(['heading' => !$hideTitle && $post_title != "" ? $post_title : ''])
    <div class="c-card__body">
        {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $post_content)) !!}
    </div>
@endcard