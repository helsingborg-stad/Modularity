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
    <?php


        $postData = array(
            'posts_data_source' => isset($posts_data_source),
            'posts_fields' => isset($posts_fields),
            'archive_link' => isset($archive_link),
            'archive_link_url' => isset($archive_link_url),
            'filters' => isset($filters)
        );
    ?>
    @listing([
        'list' => (new Modularity\Module\Posts\Helper\PrepareList)->prepare($posts, $postData)
    ])
    @endlisting

</div>
