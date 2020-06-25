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
    <?php (new Modularity\Module\Posts\TemplateController\ListTemplate)->prepareList($posts,
    $postData = [
            'posts_data_source' => $posts_data_source,
            'archive_link' => $archive_link,
            'archive_link_url' => $archive_link_url,
            'filters' => $filters
        ]);
    ?>
    @listing([
        'list' => (new Modularity\Module\Posts\TemplateController\ListTemplate)->prepareList
        ($posts, $postData =
        [
                'posts_data_source' => $posts_data_source,
                'archive_link' => $archive_link,
                'archive_link_url' => $archive_link_url,
                'filters' => $filters
        ])
    ])
    @endlisting

</div>
