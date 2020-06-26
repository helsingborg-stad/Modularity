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
    {{-- 'list' => (new Modularity\Module\Posts\TemplateController\ListTemplate)->prepare($posts,
     $postData = array( --}}
    @listing([
        'list' => (new Modularity\Module\Posts\Helper\PrepareList)->prepare($posts, $postData = array(
            'posts_data_source' => $posts_data_source,
            'posts_fields' => $posts_fields,
            'archive_link' => $archive_link,
            'archive_link_url' => $archive_link_url,
            'filters' => $filters
        ))
    ])
    @endlisting

</div>
