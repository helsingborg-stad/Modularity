<?php
    $fields = json_decode(json_encode(get_fields($module->ID)));

    $sortBy = $fields->sorted_by ? $fields->sorted_by : 'date';
    $order = $fields->order ? $fields->order : 'desc';

    $getPostsArgs = array(
        'post_type' => $fields->post_type,
        'posts_per_page' => $fields->number_of_posts,
        'orderby' => $sortBy,
        'order' => $order
    );

    if ($fields->taxonomy_filter === true) {
        $taxType = $fields->filter_posts_taxonomy_type;
        $taxValues = (array) $fields->filter_posts_by_tag;

        foreach ($taxValues as $term) {
            $getPostsArgs['tax_query'][] = array(
                'taxonomy' => $taxType,
                'field'    => 'name',
                'terms'    => $term
            );
        }
    }

    $posts = get_posts($getPostsArgs);

    if (isset($fields->view_as) && $fields->view_as == 'list') {
        include 'modularity-mod-latest-list.php';
    }
    elseif (isset($fields->view_as) && $fields->view_as == 'news') {
        include 'modularity-mod-latest-news.php';
    } else {
        include 'modularity-mod-latest-item.php';
    }
?>
